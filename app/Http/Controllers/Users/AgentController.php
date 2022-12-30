<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DepotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Depot;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Range;
use App\Models\Status;
use App\Models\User;
use App\Models\Customer;
use App\Models\Warranty;
use App\Exports\ExcelsExport;
use App\Events\CreateNotifiEvent;
use App\Models\Notification;
use App\Rules\CustomerEmailExist;
use App\Rules\ProductExist;

class AgentController extends Controller
{
    public function index()
    {
        return redirect()->route('agent.depot_product');
    }

    public function depot_product()
    {
        $ranges = Range::all();
        $depots = Depot::where('owner_id', Auth::user()->id)->get();
        $results = [];
        foreach ($depots as $depot) {
            foreach ($ranges as $range) {
                $count_product = Product::count_quantity_product(['range_id', 'depot_id', 'status_id', 'is_recall'], [$range->id, $depot->id, 2, 0]);
                $count_products = Product::count_quantity_product(['depot_id', 'status_id'], [$depot->id, 2]);
                if ($count_product)
                    $results[] = ['range' => $range, 'depot' => $depot, 'quantity' => $count_product, 'available' => $count_products];
            }
        }
        return view('agent.depot_product', compact('results'));
    }

    public function waiting_products()
    {
        $arr_transfer_batch = [];
        $group_transfer_batch_products =
            DB::table('waiting_products')
            ->where([
                ['status', '=', 0],
                ['agent_id', '=', Auth::user()->id],
            ])
            ->groupBy('transfer_batch')->get();

        foreach ($group_transfer_batch_products as $transfer_batch_product) {
            $count_transfer_batch =
                DB::table('waiting_products')
                ->select('transfer_batch')
                ->where([
                    ['status', '=', 0],
                    ['agent_id', '=', Auth::user()->id],
                    ['transfer_batch', '=', $transfer_batch_product->transfer_batch],
                ])
                ->count();
            $arr_temp = [
                'transfer_batch' => $transfer_batch_product->transfer_batch,
                'range_id' => $transfer_batch_product->range_id,
                'quantity' => $count_transfer_batch,
                'created_at' => $transfer_batch_product->created_at
            ];
            array_push($arr_transfer_batch, $arr_temp);
        }
        $depots = DB::table('depots')->where('owner_id', '=', Auth::user()->id)->get();
        return view('agent.waiting_products', compact('arr_transfer_batch', 'depots'));
    }

    public function transfer_to_depot(Request $request)
    {
        $request->validate(
            [
                'transfer_batch' => 'required|gt:0',
                'depot' => 'required|gte:0',
                'quantity_to_depot' => 'required|gte:0',
                'quantity' => 'required|gte:0',
            ],
        );

        if ($request->input('quantity') < $request->input('quantity_to_depot')) {
            return response()->json(['type' => 'error', 'message' => 'quantity is invalid']);
        }

        $is_true_depot = Depot::depot_check_have($request->user_id, $request->input('depot'));

        if (!$is_true_depot) {
            return response()->json(['type' => 'error', 'message' => 'depot is invalid']);
        }

        $still_empty = Depot::depot_check_still_empty($request->input('depot'), $request->input('quantity_to_depot'));

        if (!$still_empty) {
            return response()->json(['type' => 'error', 'message' => 'depot is not enough']);
        }

        $transfer_batch_products = DB::table('waiting_products')
            ->where([
                ['transfer_batch', '=', $request->input('transfer_batch')],
                ['status', '=', 0]
            ])
            ->get();

        $n = 0;
        foreach ($transfer_batch_products as $transfer_batch_product) {
            if ($n >= $request->input('quantity_to_depot')) {
                return response()->json(['type' => 'success', 'message' => 'transfer product success']);
            }

            $n++;

            DB::table('waiting_products')
                ->where('product_id', '=', $transfer_batch_product->product_id)
                ->update([
                    'status' => 1,
                ]);
            DB::table('products')
                ->where('id', '=', $transfer_batch_product->product_id)
                ->update([
                    'depot_id' => $request->input('depot'),
                ]);
        }
        return response()->json(['type' => 'success', 'message' => 'transfer product success',]);
    }


    public function sell_to_customer()
    {
        return view('agent.sell_to_customer');
    }

    public function check_sell_to_customer(Request $request)
    {
        $request->validate(
            [
                'customer_email' => ['required', 'email', new CustomerEmailExist],
                'product_id' => ['required', 'gt:0', new ProductExist],
            ],
        );

        $check_product = Product::check_product_exist($request->input('product_id'));

        $customer = DB::table('users')->where('email', $request->input('customer_email'))->first();
        $product = DB::table('products')->where('id', $request->input('product_id'))->first();
        $address_customer = (DB::table('addresses')->where('id', '=', $customer->address_id)->first());
        $range_name = (DB::table('ranges')->where('id', $product->range_id)->first())->name;

        if ($product->status_id != 2 || $product->agent_id != Auth::user()->id) {
            return redirect()->back()->with(['error' => 'Sản phẩm không có trong kho']);
        }

        if ($product->is_recall == 1) {
            return redirect()->back()->with(['error' => 'Sản phẩm đang được thu hồi']);
        }

        return view('agent.check_sell_to_customer', compact('customer', 'product', 'address_customer', 'range_name'));
    }

    public function confirm_sell_to_customer(Request $request)
    {
        $message = Product::transfer_product(Auth::user()->id, $request->input('user_id_to'), $request->input('product_id'), 3);
        return redirect()->route('agent.sell_to_customer')->with(['message' => $message]);
    }

    public function show_product_warranty()
    {
        $products_to_warranty = Product::get_product(['agent_id', 'status_id', 'is_recall'], [Auth::user()->id, 4, 0]);
        $products_to_customer = Product::get_product(['agent_id', 'status_id', 'is_recall'], [Auth::user()->id, 7, 0]);
        $warranties = DB::table('users')->where('role_id', 3)->get();

        return view('agent.show_product_warranty', compact('products_to_warranty', 'products_to_customer', 'warranties'));
    }

    public function transfer_error_prod_to_warranty(Request $request)
    {
        $request->validate(
            [
                'warranty_id' => 'required|gt:0',
                'product_id' => 'required|gt:0',
            ],
        );

        $check_product = Product::check_product_exist($request->input('product_id'));
        $check_warranty = Warranty::check_warranty_exist($request->input('warranty_id'));

        if (!$check_warranty || !$check_product) {
            // return redirect()->back()->with(['message' => 'Trung tâm bảo hành hoặc sản phẩm không tồn tại. Vui lòng kiểm tra lại !!!']);
            return response()->json(['type' => 'error', 'message' => 'trung tâm bảo hành không tồn tại']);
        }

        $warranty = DB::table('users')->where('id', $request->input('warranty_id'))->first();
        $product = DB::table('products')->where('id', $request->input('product_id'))->first();

        if ($product->status_id != 4 || $product->agent_id != $request->user_id || $product->is_recall == 1) {
            // return redirect()->back()->with(['message' => 'Không thấy sản phẩm, vui lòng liên hệ với quản trị viên !!!']);
            return response()->json(['type' => 'error', 'message' => 'không tìm thấy sản phẩm']);
        }

        Product::transfer_product($request->user_id, $request->input('warranty_id'), $product->id, 5);
        // return redirect()->back()->with(['type' => 'success', 'message' => 'đã đến trung tâm bảo hành']);
        return response()->json(['type' => 'success', 'message' => 'chuyển sản phẩm thành công']);
    }

    public function transfer_error_prod_return_to_customer(Request $request)
    {
        $request->validate(
            [
                'product_id' => 'required|gt:0',
            ],
        );

        $check_product = Product::check_product_exist($request->input('product_id'));

        if (!$check_product) {
            return response()->json(['type' => 'error', 'message' => 'sản phẩm không tồn tại']);
        }

        $product = DB::table('products')->where('id', $request->input('product_id'))->first();

        if ($product->status_id != 7 || $product->agent_id != $request->user_id || $product->is_recall == 1) {
            return response()->json(['type' => 'error', 'message' => 'sản phẩm không tồn tại']);
        }

        Product::transfer_product($request->user_id, $product->customer_id, $product->id, 3, ['return_prod' => 'Trả lại sản phẩm cho người dùng']);
        return response()->json(['type' => 'success', 'message' => 'trả sản phẩm thành công']);
    }

    public function agent_depots()
    {
        $depots = DepotController::get_all_depots(Auth::user()->id);
        return view('agent.agent_depots', ['depots' => $depots]);
    }

    public function add_agent_depot()
    {
        return view('agent.add_agent_depot');
    }

    public function post_add_agent_depot(Request $request)
    {
        DepotController::add_depot($request, Auth::user()->id);
        return redirect()->route('agent.agent_depots')->with(['message' => 'Tạo kho thành công']);
    }

    public function put_edit_agent_depot(Request $request)
    {
        DepotController::edit_depot($request, 2);
        return response()->json(true);
    }


    
    public function product_statistic()
    {
        return view('agent.product_statistic', ['statuses' => Status::all()]);
    }

    public function print_product_statistic(Request $request)
    {
        $list_products = [];
        $data_inputs = $request->all();
        unset($data_inputs['_token']);
        $excel = new ExcelsExport();
        if (count($data_inputs) == 0)
            $list_products[] = Product::excel_export(Product::where("agent_id", Auth::user()->id)->get());
        else {
            foreach ($data_inputs as $key => $data_input) {
                foreach ($data_input as $input_value) {
                    if ($input_value != 0) {
                        if ($key == 'months') {
                            $list_products[] = Product::excel_export_product_by_month(
                            Product::where("agent_id", Auth::user()->id)->whereMonth("created_at", $input_value)->get());
                        } else if ($key == 'status') {
                            $list_products[] = Product::excel_export(Product::where("agent_id", Auth::user()->id)
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
                            Product::where("agent_id", Auth::user()->id)
                            ->whereBetween("created_at", [$from, $to])->get());
                        } else if ($key == 'year') {
                            $list_products[] = Product::excel_export_product_by_year(
                            Product::where("agent_id", Auth::user()->id)
                            ->whereYear("created_at", $input_value)->get());
                        }
                    }
                }
            }
        }
        $excel->setSheets($list_products);
        $excel->setHeadings( ['Id', 'Status', 'Name', 'Property', 'Factory', 'Agent', 'Created at']);
        return $excel->download('product_agent.xlsx');
    }

    public function product_sales_statistic()
    {
        return view('agent.product_sales_statistic', ['statuses' => Status::all()]);
    }

    public function print_product_sales_statistic(Request $request) {
        $list_products = [];
        $data_inputs = $request->all();
        unset($data_inputs['_token']);
        $excel = new ExcelsExport();
        if (count($data_inputs) == 0)
            $list_products[] = Product::excel_export(Product::where("agent_id", Auth::user()->id)->get(), 'customer_by_time');
        else {
            foreach ($data_inputs as $key => $data_input) {
                foreach ($data_input as $input_value) {
                    if ($input_value != 0) {
                        if ($key == 'months') {
                            $list_products[] = Product::excel_export_product_by_month(
                            Product::where("agent_id", Auth::user()->id)->whereMonth("customer_buy_time", $input_value)->get(),
                            'customer_by_time');
                        } else if ($key == 'status') {
                            $list_products[] = Product::excel_export(Product::where("agent_id", Auth::user()->id)
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
                            Product::where("agent_id", Auth::user()->id)
                            ->whereBetween("customer_buy_time", [$from, $to])->get(), 'customer_by_time');
                        } else if ($key == 'year') {
                            $list_products[] = Product::excel_export_product_by_year(
                            Product::where("agent_id", Auth::user()->id)
                            ->whereYear("customer_buy_time", $input_value)->get(), 'customer_by_time');
                        }
                    }
                }
            }
        }
        $excel->setSheets($list_products);
        $excel->setHeadings( ['Id', 'Status', 'Name', 'Property', 'Factory', 'Agent', 'Customer buy time']);
        return $excel->download('product_sales_agent.xlsx');
    }

}
