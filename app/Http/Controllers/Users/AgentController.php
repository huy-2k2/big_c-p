<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DepotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Depot;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warranty;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:agent']);
    }

    public function index()
    {
        return view('agent.main');
    }

    public function depot_product() {
        $lines = DB::table('ranges')->get();
        $depots = DB::table('depots')->where('owner_id', '=', Auth::user()->id)->get();
        return view('agent.depot_product', compact('lines', 'depots'));
    }

    public function waiting_products() {
        $arr_transfer_batch = [];
        $group_transfer_batch_products = 
                DB::table('waiting_products')
                ->where([
                    ['status', '=', 0], 
                    ['agent_id', '=', Auth::user()->id],
                ])
                ->groupBy('transfer_batch')->get();
        
        foreach($group_transfer_batch_products as $transfer_batch_product) {
            $count_transfer_batch = 
                DB::table('waiting_products')
                ->select('transfer_batch')
                ->where([
                    ['status', '=', 0], 
                    ['agent_id', '=', Auth::user()->id],
                    ['transfer_batch', '=', $transfer_batch_product -> transfer_batch],
                ])
                ->count();
            $arr_temp = ['transfer_batch' => $transfer_batch_product -> transfer_batch, 
                            'range_id' => $transfer_batch_product -> range_id,
                            'quantity' => $count_transfer_batch,
                            'created_at' => $transfer_batch_product -> created_at
                        ];
            array_push($arr_transfer_batch, $arr_temp);
        }
        $depots = DB::table('depots')->where('owner_id', '=', Auth::user()->id)->get();
        return view('agent.waiting_products', compact('arr_transfer_batch', 'depots'));
    }

    public function transfer_to_depot(Request $request) {
        $request->validate(
            [
                'transfer_batch'=>'required|gt:0',
                'depot'=>'required|gte:0',
                'quantity_to_depot'=>'required|gte:0',
                'quantity'=>'required|gte:0',
            ],
            [
                'depot.required'=>'Vui lòng nhập trường này',
                'quantity.required'=>'Vui lòng nhập trường này',
                'transfer_batch.required'=>'Vui lòng nhập trường này',
                'quantity_to_depot.required'=>'Vui lòng nhập số lượng',
                'depot.gte'=>'Vui lòng nhập đúng',
                'transfer_batch.gt'=>'Vui lòng nhập đúng',
                'quantity_to_depot.gte'=>'Số lượng phải lớn hơn 0',
                'quantity.gte'=>'Số lượng phải lớn hơn 0',
            ]
        );

        if($request -> input('quantity') < $request -> input('quantity_to_depot')) {
            return redirect() -> back() -> with(['message' => 'Nhập số lượng lớn hơn số lượng trong lô']);
        }

        $is_true_depot = Depot::depot_check_have(Auth::user()->id, $request->input('depot'));

        if(!$is_true_depot) {
            return redirect() -> back() -> with(['message' => 'Không có kho này']);
        }

        $still_empty = Depot::depot_check_still_empty($request->input('depot'), $request->input('quantity_to_depot'));

        if(!$still_empty) {
            return redirect() -> back() -> with(['message' => 'Kho không đủ chỗ chứa, vui lòng kiểm tra lại']);
        }
        
        $transfer_batch_products = DB::table('waiting_products')
                                    ->where([
                                        ['transfer_batch', '=', $request->input('transfer_batch')],
                                        ['status', '=', 0]
                                    ])
                                    ->get();
        
        $n = 0;
        foreach($transfer_batch_products as $transfer_batch_product) {
            if($n >= $request->input('quantity_to_depot')) {
                return redirect() -> back() -> with(['message' => 'Chuyển sp vào kho thành công']);
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
        return redirect() -> back() -> with(['message' => 'Chuyển sp vào kho thành công']);

    }
    

    public function sell_to_customer() {
        return view('agent.sell_to_customer');
    }

    public function check_sell_to_customer(Request $request) {
        $request->validate(
            [
                'customer_id'=>'required|gt:0',
                'product_id'=>'required|gt:0',
            ],
            [
                'product_id.required'=>'Vui lòng nhập trường này',
                'customer_id.required'=>'Vui lòng nhập trường này',
                'product_id.gt'=>'Vui lòng nhập đúng định dạng',
                'customer_id.gt'=>'Vui lòng nhập đúng định dạng',
            ]
        );

        $check_customer = Customer::check_customer_exist($request->input('customer_id'));
        $check_product = Product::check_product_exist($request->input('product_id'));

        if(!$check_customer || !$check_product) {
            return redirect()->back()->with(['message'=>'Người dùng hoặc sản phẩm không tồn tại. Vui lòng kiểm tra lại !!!']);
        } 

        $customer = DB::table('users')->where('id', $request->input('customer_id'))->first();
        $product = DB::table('products')->where('id', $request->input('product_id'))->first();
        $address_customer = (DB::table('addresses')->where('id', '=', $customer->address_id)->first());
        $range_name = (DB::table('ranges')->where('id', $product->range_id)->first())->name;

        if($product->status_id != 2 || $product->agent_id != Auth::user()->id) {
            return redirect()->back()->with(['message'=>'Sản phẩm không có trong kho']);
        }

        return view('agent.check_sell_to_customer', compact('customer', 'product', 'address_customer', 'range_name'));
    }

    public function confirm_sell_to_customer(Request $request) {
        $message = Product::transfer_product(Auth::user()->id, $request->input('user_id_to'), $request->input('product_id'), 3);
        return redirect() -> route('home') -> with(['message' => $message]);
    }

    public function show_product_warranty() {
        $products_to_warranty = Product::get_product(['agent_id', 'status_id'], [Auth::user()->id, 4]);
        $products_to_customer = Product::get_product(['agent_id', 'status_id'], [Auth::user()->id, 7]);
        $warranties = DB::table('users')->where('role_id', 3)->get();

        return view('agent.show_product_warranty', compact('products_to_warranty', 'products_to_customer', 'warranties'));
    }

    public function transfer_error_prod_to_warranty(Request $request) {
        $request->validate(
            [
                'warranty_id'=>'required|gt:0',
                'product_id'=>'required|gt:0',
            ],
            [
                'product_id.required'=>'Vui lòng nhập trường này',
                'warranty_id.required'=>'Vui lòng nhập trường này',
                'product_id.gt'=>'Vui lòng nhập đúng định dạng',
                'warranty_id.gt'=>'Vui lòng nhập đúng định dạng',
            ]
        );

        $check_product = Product::check_product_exist($request->input('product_id'));
        $check_warranty = Warranty::check_warranty_exist($request->input('warranty_id'));

        if(!$check_warranty || !$check_product) {
            return redirect()->back()->with(['message'=>'Trung tâm bảo hành hoặc sản phẩm không tồn tại. Vui lòng kiểm tra lại !!!']);
        } 

        $warranty = DB::table('users')->where('id', $request->input('warranty_id'))->first();
        $product = DB::table('products')->where('id', $request->input('product_id'))->first();

        if($product->status_id != 4 || $product->agent_id != Auth::user()->id) {
            return redirect()->back()->with(['message'=>'Không thấy sản phẩm, vui lòng liên hệ với quản trị viên !!!']);
        }

        $message = Product::transfer_product(Auth::user()->id, $product->warranty_id, $product->id, 5);
        return redirect() -> route('home') -> with(['message' => $message]);
    }

    public function transfer_error_prod_return_to_customer(Request $request) {

    }
}
