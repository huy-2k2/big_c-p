<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
  public function __construct()
  {
      $this->middleware(['author:customer']);
  }

  public function index()
  {
      return view('customer.main');
  }

  public function show_product() {
    $products = Product::get_product(['customer_id'], [Auth::user()->id]);
   
    return view('customer.show_product', compact('products'));
  }

  public function warranty_claim($id) {
    $product = Product::find($id);
    return view('customer.warranty_claim', compact('product'));
  }

  public function send_warranty_claim(Request $request) {
    $request->validate(
      [
          'claim_reason'=>'required|string',
          'product_id'=>'required|gt:0',
      ],
      [
          'product_id.required'=>'Vui lòng nhập trường này',
          'claim_reason.required'=>'Vui lòng nhập trường này',
          'product_id.gt'=>'Vui lòng nhập đúng định dạng',
          'claim_reason.string'=>'Vui lòng nhập đúng định dạng',
      ]
      );

      $check_product = Product::check_product_exist($request->input('product_id'));

      if(!$check_product) {
        return redirect()->back()->with(['message'=>'Sản phẩm không tồn tại. Vui lòng kiểm tra lại !!!']);
      }

      $product = DB::table('products')->where('id', $request->input('product_id'))->first();

      if($product->status_id != 3 || $product->customer_id != Auth::user()->id) {
          return redirect()->back()->with(['message'=>'Hiện tại đang không sở hữu sản phẩm này']);
      }

      $message = Product::transfer_product(Auth::user()->id, $product->agent_id, $product->id, 4, ['reason' => $request->input('claim_reason')]);
      return redirect() -> route('home') -> with(['message' => $message]);
  }
}
