<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Warranty;
use App\Http\Requests\StoreWarrantyRequest;
use App\Http\Requests\UpdateWarrantyRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
