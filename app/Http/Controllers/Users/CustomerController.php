<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Warranty;
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
    return redirect()->route('customer.show_product');
  }

  public function show_product()
  {
    $products = Product::get_product(['customer_id', 'is_recall'], [Auth::user()->id, 0]);

    return view('customer.show_product', compact('products'));
  }

  public function warranty_claim($id)
  {
    $product = Product::find($id);
    if ($product->out_of_warranty == 1) {
      return redirect()->back()->with(['message' => 'Sản phẩm đã hết hạn bảo hành']);
    }
    $product_errors = DB::table('product_errors')->get();
    return view('customer.warranty_claim', compact('product', 'product_errors'));
  }

  public function send_warranty_claim(Request $request)
  {
    $request->validate(
      [
        'claim_reason' => 'required|string',
        'product_id' => 'required|gt:0',
        'error_id' => 'required|gt:0',

      ],
      [
        'error_id.required' => 'Vui lòng nhập trường này',
        'claim_reason.required' => 'Vui lòng nhập trường này',
        'error_id.gt' => 'Vui lòng nhập đúng định dạng',
        'claim_reason.string' => 'Vui lòng nhập đúng định dạng',
        'product_id.required' => 'Vui lòng nhập trường này',
        'product_id.gt' => 'Vui lòng nhập đúng định dạng',
      ]
    );

    $check_product = Product::check_product_exist($request->input('product_id'));

    if (!$check_product) {
      return redirect()->back()->with(['message' => 'Sản phẩm không tồn tại. Vui lòng kiểm tra lại !!!']);
    }

    $product = DB::table('products')->where('id', $request->input('product_id'))->first();

    if ($product->status_id != 3 || $product->customer_id != Auth::user()->id || $product->is_recall == 1) {
      return redirect()->back()->with(['message' => 'Hiện tại đang không sở hữu sản phẩm này']);
    }

    $message = Product::transfer_product(Auth::user()->id, $product->agent_id, $product->id, 4, ['reason' => $request->input('claim_reason'), 'error_id' => $request->input('error_id'), 'batch_id' => $product->batch_id]);

    $product_error_codes = DB::table('product_errors')->get();
    $count_with_error_code = [];
    //batch_id
    foreach ($product_error_codes as $product_error_code) {
      $count = DB::table('warranty_products')
        ->where([
          ['batch_id', '=', $product->batch_id],
          ['product_error_id', $product_error_code->id],
          ['status', '=', 0]
        ])->count();
      $count_with_error_code[$product_error_code->id] = $count;
    }

    foreach ($count_with_error_code as $count_one_error_code) {
      if ($count_one_error_code >= 3) {
        Warranty::product_recall($product->batch_id);
        break;
      }
    }

    return redirect()->route('customer.show_product')->with(['message' => $message]);
  }
}
