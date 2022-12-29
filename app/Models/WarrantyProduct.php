<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyProduct extends Model
{
    use HasFactory;

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function getProductName($warrantyProducts) {
        $products = [];
        foreach ($warrantyProducts as $warrantyProduct) {
            if ($warrantyProduct->product->range_id != null)
            {
                $product = $warrantyProduct->product->range_id;
                array_push($products, $product);
            }
        }
        return $products;
    }
    public static function getProductFactory($warrantyProducts) 
    {
        $products = [];
        foreach ($warrantyProducts as $warrantyProduct) {
            if ($warrantyProduct->product->factory != null)
            {
                $product = $warrantyProduct->product->factory->user_id;
                array_push($products, $product);
            }
        }
        return $products;
    }

    public static function getProductAgent($warrantyProducts) 
    {
        $products = [];
        foreach ($warrantyProducts as $warrantyProduct) {
            if ($warrantyProduct->product->agent != null)
            {
                $product = $warrantyProduct->product->agent->user_id;
                array_push($products, $product);
            }
        }
        return $products;
    }    

}
