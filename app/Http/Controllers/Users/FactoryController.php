<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\BatchController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Range;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use App\Exports\ExcelsExport;
use App\Events\CreateNotifiEvent;
use App\Models\Notification;

class FactoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:factory']);
    }

    public function index()
    {
        return view('factory.main');
    }

    public function create_batch() {
        $ranges = DB::table('ranges')->get();
        $depots = DB::table('depots')->where('owner_id', Auth::user()->id)->get();
        
        return view('factory.create_batch', compact('ranges', 'depots'));
    }

    public function create_batch_post(Request $request) {
        $temp = BatchController::create_batch($request, Auth::user()->id);
        if(!$temp) {
            return redirect()->route('factory.depot_product') ->with(['message' => 'Kho không đủ chỗ trống, vui lòng kiểm tra lại']);
        } else {
            return redirect()->route('factory.depot_product') ->with(['message' => 'Tạo lô thành công']);
        }
    }

    //depot
    public function factory_depots() {
        $depots = DepotController::get_all_depots(Auth::user()->id);
    
        return view('factory.factory_depots', compact('depots'));
    }

    public function add_factory_depot() {
        return view('factory.add_factory_depot');
    }

    public function post_add_factory_depot(Request $request) {
        DepotController::add_depot($request, Auth::user()->id);

        return redirect()->route('factory.factory_depots')->with(['message' => 'Tạo kho thành công']);
    }

    public function delete_factory_depot($id) {
        DepotController::delete_depot($id); 
        return redirect()->route('factory.factory_depots')->with(['message' => 'Xóa kho thành công']);
    }

    public function edit_factory_depot($id) {
        $depot_edit = DB::table('depots')->where('id', '=', $id)->get()->first();
        if($depot_edit) {
            return view('factory.edit_factory_depot', compact('depot_edit'));
        } else {
            return redirect()->route('factory.factory_depots')->with(['message' => 'Không tồn tại']);
        }
    }

    public function put_edit_factory_depot($id, Request $request) {
        DepotController::edit_depot($id, $request); 

        return redirect()->route('factory.factory_depots')->with(['message' => 'Chỉnh sửa thành công']);
    }

    public function depot_product() {
        $lines = DB::table('ranges')->get();
        $depots = DB::table('depots')->where('owner_id', '=', Auth::user()->id)->get();
        
        return view('factory.depot_product', compact('lines', 'depots'));
    }

    public static function create_notifi_to_agent($title, $content, $agent_id) {
        $notification = Notification::create([
            'title' => $title,
            'content' => $content
        ]);

        $notification->users()->attach($agent_id);
        broadcast(new CreateNotifiEvent(['user_id' => $agent_id, 'notification' => $notification, 'time' => $notification->created_at->toDateTimeString()]));
        
        return redirect()->back()->with(['message' => 'tạo thông báo thành công']);
    }

    public function transfer_prod_to_agent() {
        $lines = DB::table('ranges')->get();
        $agents = DB::table('agents')->get();
        return view('factory.transfer_prod_to_agent', compact('lines', 'agents'));
    }

    public function post_transfer_prod_to_agent(Request $request) {
        $request->validate(
            [
                'quantity_prod'=>'required|gt:0',
                'range'=>'required|gte:0',
                'agent'=>'required|gte:0',
            ],
            [
                'range.required'=>'Vui lòng nhập trường này',
                'quantity_prod.required'=>'Vui lòng nhập trường này',
                'agent.required'=>'Vui lòng nhập trường này',
                'range.gte'=>'Vui lòng nhập đúng',
                'quantity_prod.gt'=>'Số lượng phải lớn hơn 0',
                'agent.gte'=>'Vui lòng nhập đúng',
            ]
        );
        
        $count_prod_in_depot = Product::count_quantity_product(['range_id', 'factory_id', 'status_id', 'is_recall'], [$request->input('range'), Auth::user() -> id, 1, 0]);
        if($count_prod_in_depot >= $request->input('quantity_prod')) {
            $all_prod_in_depot = Product::get_product(['range_id', 'factory_id', 'status_id', 'is_recall'], [$request->input('range'), Auth::user() -> id, 1, 0]);
            $i = 0;
            $transfer_batch = ($all_prod_in_depot->first())->id;

            $range_name = (DB::table('ranges')->where('id', '=', $request->input('range'))->first())->name;
            $content = 'Nhà máy ' . Auth::user()->name . ' đã xuất ' . $request->input('quantity_prod') . ' sản phẩm dòng ' . $range_name . ' cho bạn';
            $factory_function = new FactoryController();
            $factory_function::create_notifi_to_agent('Nhận được sản phẩm từ nhà máy', $content, $request->input('agent')); //cần sửa
            
            foreach($all_prod_in_depot as $prod) {
                
                if ($i == $request->input('quantity_prod')) { 
                    return redirect()->route('factory.transfer_prod_to_agent')->with(['message' => 'xuất thành công']);
                }

                $i++;
                
                DB::table('waiting_products') -> insert([
                    'product_id' => $prod->id,
                    'agent_id' => $request->input('agent'),
                    'range_id' => $request->input('range'),
                    'transfer_batch' => $transfer_batch,
                    'status' => 0, 
                    'created_at' => Carbon::now(),
                ]);
                DB::table('products')->where('id', $prod->id)->update([
                    'agent_id' => $request->input('agent'),
                    'status_id' => 2,
                    'owner_id' => $request->input('agent'),
                    'created_at' => Carbon::now()
                ]);
            }

            return redirect() -> back() -> with(['message' => 'Chuyển kho thành công']);
        } else {
            return redirect() -> back() -> with(['message' => 'Số lượng hàng tồn kho không đủ !!!']);
        }
    }

    public function product_statistic()
    {
        return view('factory.product_statistic', ['statuses' => Status::all()]);
    }

    public function print_product_statistic(Request $request)
    {
        $list_products = [];
        $data_inputs = $request->all();
        unset($data_inputs['_token']);
        $excel = new ExcelsExport();
        if (count($data_inputs) == 0)
            $list_products[] = Product::excel_export(Product::where("factory_id", Auth::user()->id)->get());
        else {
            foreach ($data_inputs as $key => $data_input) {
                foreach ($data_input as $input_value) {
                    if ($input_value != 0) {
                        if ($key == 'months') {
                            $list_products[] = Product::excel_export_product_by_month(
                            Product::where("factory_id", Auth::user()->id)->whereMonth("created_at", $input_value)->get());
                        } else if ($key == 'status') {
                            $list_products[] = Product::excel_export(Product::where("factory_id", Auth::user()->id)
                            ->where("{$key}_id", $input_value)->get());
                        } else if ($key == 'quarter') {
                            $from = 0;
                            $to = 0;
                            switch($input_value) {
                                case('1') : 
                                    $from = Carbon::now()->startOfYear(); 
                                    $to = Carbon::now()->startOfYear()->addMonth(2);
                                    break;
                                case('2') : 
                                    $from = Carbon::now()->startOfYear()->addMonth(3);
                                    $to = Carbon::now()->startOfYear()->addMonth(5);
                                    break;
                                case('3') :
                                    $from = Carbon::now()->startOfYear()->addMonth(6);
                                    $to = Carbon::now()->startOfYear()->addMonth(8);
                                    break;
                                case("4") : 
                                    $from = Carbon::now()->startOfYear()->addMonth(9);
                                    $to = Carbon::now()->startOfYear()->addMonth(11);
                                    break;
                                default: 
                            }
                    
                            $list_products[] = Product::excel_export_product_by_quarter(
                            Product::where("factory_id", Auth::user()->id)
                            ->whereBetween("created_at", [$from, $to])->get());
                        } else if ($key == 'year') {
                            $list_products[] = Product::excel_export_product_by_year(
                            Product::where("factory_id", Auth::user()->id)
                            ->whereYear("created_at", $input_value)->get());
                        }
                    }
                }
            }
        }
        $excel->setSheets($list_products);
        //dd($list_products);
        $excel->setHeadings( ['Id', 'Status', 'Name', 'Property', 'Factory', 'Agent', 'Created at']);
        return $excel->download('product_factory.xlsx');
    }

    public function product_sales_statistic()
    {
        return view('factory.product_sales_statistic', ['statuses' => Status::all()]);
    }

    public function print_product_sales_statistic(Request $request) {
        $list_products = [];
        $data_inputs = $request->all();
        unset($data_inputs['_token']);
        $excel = new ExcelsExport();
        if (count($data_inputs) == 0)
            $list_products[] = Product::excel_export(Product::where("factory_id", Auth::user()->id)->get(), 'customer_by_time');
        else {
            foreach ($data_inputs as $key => $data_input) {
                foreach ($data_input as $input_value) {
                    if ($input_value != 0) {
                        if ($key == 'months') {
                            $list_products[] = Product::excel_export_product_by_month(
                            Product::where("factory_id", Auth::user()->id)->whereMonth("customer_buy_time", $input_value)->get(),
                            'customer_by_time');
                        } else if ($key == 'status') {
                            $list_products[] = Product::excel_export(Product::where("factory_id", Auth::user()->id)
                            ->where("{$key}_id", $input_value)->get(), 'customer_by_time');
                        } else if ($key == 'quarter') {
                            $from = 0;
                            $to = 0;
                            switch($input_value) {
                                case('1') : 
                                    $from = Carbon::now()->startOfYear(); 
                                    $to = Carbon::now()->startOfYear()->addMonth(2);
                                    break;
                                case('2') : 
                                    $from = Carbon::now()->startOfYear()->addMonth(3);
                                    $to = Carbon::now()->startOfYear()->addMonth(5);
                                    break;
                                case('3') :
                                    $from = Carbon::now()->startOfYear()->addMonth(6);
                                    $to = Carbon::now()->startOfYear()->addMonth(8);
                                    break;
                                case("4") : 
                                    $from = Carbon::now()->startOfYear()->addMonth(9);
                                    $to = Carbon::now()->startOfYear()->addMonth(11);
                                    break;
                                default: 
                            }
                    
                            $list_products[] = Product::excel_export_product_by_quarter(
                            Product::where("factory_id", Auth::user()->id)
                            ->whereBetween("customer_buy_time", [$from, $to])->get(), 'customer_by_time');
                        } else if ($key == 'year') {
                            $list_products[] = Product::excel_export_product_by_year(
                            Product::where("factory_id", Auth::user()->id)
                            ->whereYear("customer_buy_time", $input_value)->get(), 'customer_by_time');
                        }
                    }
                }
            }
        }
        $excel->setSheets($list_products);
        $excel->setHeadings( ['Id', 'Status', 'Name', 'Property', 'Factory', 'Agent', 'Customer buy time']);
        return $excel->download('product_sales_factory.xlsx');
    }
}
