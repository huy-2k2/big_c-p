<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public static function get_all_products($owner_id) {
        return DB::table('products')->where('owner_id', '=', $owner_id)->get();
    }
}
