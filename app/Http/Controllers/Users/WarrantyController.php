<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarrantyRequest;
use App\Http\Requests\UpdateWarrantyRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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

class WarrantyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:warranty']);
    }

    public function index()
    {
        return view('warranty.main');
    }

    public function show_product() {
        $products = Product::get_product(['warranty_id', 'status_id', 'is_recall'], [Auth::user()->id, 5, 0]);
        $warranty_products = DB::table('warranty_products')->where('status', 0)->get();
        
        $reasons = [];
        $error_name = [];

        foreach($warranty_products as $warranty_product) {
            $reasons[$warranty_product->product_id] = $warranty_product->reason;
            $error_name[$warranty_product->product_id] = (DB::table('product_errors')->where('id', $warranty_product->product_error_id)->first())->name;
        }

        return view('warranty.show_product', compact('products', 'reasons', 'error_name'));
    }

    public function return_prod_to_agent($product_id) {
        $product = Product::get_product(['id'], [$product_id])->first();
        
        if($product->status_id != 5 && $product->warranty_id != Auth::user()->id) {
            return redirect()->back()->with(['message'=>'Không tìm thấy sản phẩm']);
        }

        $message = Product::transfer_product(Auth::user()->id, $product->agent_id, $product_id, 7);
        
        return redirect()->back()->with(['message'=> $message]);
    }

    public function return_prod_to_factory($product_id) {
        $product = Product::get_product(['id'], [$product_id])->first();
        
        if($product->status_id != 5 && $product->warranty_id != Auth::user()->id) {
            return redirect()->back()->with(['message'=>'Không tìm thấy sản phẩm']);
        }

        $message = Product::transfer_product(Auth::user()->id, $product->factory_id, $product_id, 8);
        
        return redirect()->back()->with(['message'=> $message]);
        
    }

    public function product_statistic()
    {
        return view('warranty.product_statistic', ['statuses' => Status::all()]);
    }

    public function print_product_statistic(Request $request)
    {
        $list_products = [];
        $data_inputs = $request->all();
        unset($data_inputs['_token']);
        $excel = new ExcelsExport();
        $warranty_id = "warranty_id";
        if (count($data_inputs) == 0)
            $list_products[] = Product::excel_export(Product::where("warranty_id", Auth::user()->id)->get());
        else {
            foreach ($data_inputs as $key => $data_input) {
                foreach ($data_input as $input_value) {
                    if ($key == 'months') {
                        if ($input_value != 0) {
                            $list_products[] = Product::excel_export_product_by_month(
                                Product::where($warranty_id, Auth::user()->id)
                                ->whereMonth("created_at", $input_value)->get(),
                                'created_at');
                        } else {
                            $list_products[] = Product::excel_export_product_by_month(
                                Product::where($warranty_id, Auth::user()->id)
                                ->whereMonth("created_at", "!=", null)->get(),
                                'created_at');
                        }
                    } else if ($key == 'status') {
                        if ($input_value != 0) {
                            $list_products[] = Product::excel_export(Product::where($warranty_id, Auth::user()->id)
                            ->where("{$key}_id", $input_value)->get(), 'created_at');
                        } else {
                            $list_products[] = Product::excel_export(Product::where($warranty_id, Auth::user()->id)
                        ->where("{$key}_id", "!=", null)->get(), 'created_at');
                        }
                    } else if ($key == 'quarter') {
                        $from = 0;
                        $to = 0;
                        switch($input_value) {
                            case('1') : 
                                $from = Carbon::now()->startOfYear(); 
                                $to = Carbon::now()->startOfYear()->addMonth(2)->endOfMonth();
                                break;
                            case('2') : 
                                $from = Carbon::now()->startOfYear()->addMonth(3);
                                $to = Carbon::now()->startOfYear()->addMonth(5)->endOfMonth();
                                break;
                            case('3') :
                                $from = Carbon::now()->startOfYear()->addMonth(6);
                                $to = Carbon::now()->startOfYear()->addMonth(8)->endOfMonth();
                                break;
                            case("4") : 
                                $from = Carbon::now()->startOfYear()->addMonth(9);
                                $to = Carbon::now()->startOfYear()->addMonth(11)->endOfMonth();
                                break;
                            default: 
                        }
                        if ($input_value != 0) {
                            $list_products[] = Product::excel_export_product_by_quarter(
                                Product::where($warranty_id, Auth::user()->id)
                                ->whereBetween("created_at", [$from, $to])->get(), 'created_at');
                        } else {
                            $list_products[] = Product::excel_export_product_by_quarter(
                                Product::where($warranty_id, Auth::user()->id)
                                ->whereYear("created_at", Carbon::now()->year)->get(), 'created_at');
                        }
                    } else if ($key == 'year') {
                        if ($input_value != 0) {
                            $list_products[] = Product::excel_export_product_by_year(
                                Product::where($warranty_id, Auth::user()->id)
                                ->whereYear("created_at", $input_value)->get(), 'created_at');
                        } else {
                            $list_products[] = Product::excel_export_product_by_year(
                                Product::where($warranty_id, Auth::user()->id)
                                ->whereYear('created_at', '!=', 0)->get(), 'created_at');
                        }
                    }
                }
            }
        }
        $excel->setSheets($list_products);
        $excel->setHeadings( ['Id', 'Status', 'Name', 'Property', 'Factory', 'Agent', 'Created at']);
        return $excel->download('product_agent.xlsx');
    }

}
