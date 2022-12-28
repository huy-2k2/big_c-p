<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['depot_id', 'batch_id', 'agent_id', 'customer_id', 'factory_id', 'status', 'warranty_id'];

    public static function count_quantity_product($names, $id_values)
    {
        $count_result = DB::table('products')->where(function ($query) use ($names, $id_values) {
            for ($i = 0; $i < count($names); $i++) {
                $query->where($names[$i], '=', $id_values[$i]);
            }
        })->get()->count();

        return $count_result;
    }

    public static function get_product($names, $id_values)
    {
        $result = DB::table('products')->where(function ($query) use ($names, $id_values) {
            for ($i = 0; $i < count($names); $i++) {
                $query->where($names[$i], '=', $id_values[$i]);
            }
        })->get();

        return $result;
    }


    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'user_id');
    }

    public function agent() 
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'user_id');
    }

    public static function get_with_pivot_condition($table, $name)
    {
        return Self::whereHas($table, function (Builder $query) use ($name) {
            $query->where('name', $name);
        })->get();
    }

    public function getCreatedAtAttribute($date)
    {
        if ($date != null) {
            $format = new DateTime($date);
            $format->format('Y-m-d H:i:s');
            return $format;
        }
        return null;
    }
    
    public function getUpdatedAtAttribute($date)
    {
        if ($date != null) {
            $format = new DateTime($date);
            $format->format('Y-m-d H:i:s');
            return $format;
        }
        return null;
    }

    public function getCustomerBuyTimeAttribute($date)
    {
        if ($date != null) {
            $format = new DateTime($date);
            $format->format('Y-m-d H:i:s');
            return $format;
        }
        return null;
    }

    public static function excel_export($products, $timeline = "")
    {
        foreach ($products as $product) {
            $product['status'] = $product->status->name;
            $product['rangeName'] = $product->batch->range->name;
            $product['rangeProperty'] = $product->batch->range->property;
            if (!empty($product->factory->user)) {
                $product['factory'] = $product->factory->user->name;
            }
            if (!empty($product->agent->user)) {
                $product['agent'] = $product->agent->user->name;
            } else {
                $product['agent'] = null;
            }
            if ($timeline == "customer_by_time") {
                $product['date'] = $product->customer_buy_time;
            } else {
                $product['date'] = $product->created_at;
            }
            
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time']);
        }
        return $products;
    }

    public static function excel_export_product_by_month($products, $timeline = "") {
        foreach ($products as $product) {
            $product['status'] = $product->status->name;
            $product['rangeName'] = $product->batch->range->name;
            $product['rangeProperty'] = $product->batch->range->property;
            if (!empty($product->factory->user)) {
                $product['factory'] = $product->factory->user->name;
            }
            if (!empty($product->agent->user)) {
                $product['agent'] = $product->agent->user->name;
            } else {
                $product['agent'] = null;
            }

            if ($timeline == "customer_by_time") {
                $timestamp = $product->customer_buy_time;
                $month = date_format($timestamp, 'M');
                $product['month'] = $month;
            } else {
                $timestamp = $product->created_at;
                $month = date_format($timestamp, 'M');
                $product['month'] = $month;
            }
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time']);
        }
    return $products;
    }

    public static function excel_export_product_by_quarter($products, $timeline = "") {
        foreach ($products as $product) {
            $product['status'] = $product->status->name;
            $product['rangeName'] = $product->batch->range->name;
            $product['rangeProperty'] = $product->batch->range->property;
            if (!empty($product->factory->user)) {
                $product['factory'] = $product->factory->user->name;
            }
            if (!empty($product->agent->user)) {
                $product['agent'] = $product->agent->user->name;
            } else {
                $product['agent'] = null;
            }
            
            if ($timeline == "customer_by_time") {
                $timestamp = $product->customer_buy_time;
                $month = date_format($timestamp, 'M');
                $product['month'] = $month;
            } else {
                $timestamp = $product->created_at;
                $month = date_format($timestamp, 'M');
                $product['month'] = $month;
            }

            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time']);
        }
    return $products;
    }

    public static function excel_export_product_by_year($products, $timeline = "") {
        foreach ($products as $product) {
            $product['status'] = $product->status->name;
            $product['rangeName'] = $product->batch->range->name;
            $product['rangeProperty'] = $product->batch->range->property;
            if (!empty($product->factory->user)) {
                $product['factory'] = $product->factory->user->name;
            }
            if (!empty($product->agent->user)) {
                $product['agent'] = $product->agent->user->name;
            } else {
                $product['agent'] = null;
            }
            
            if ($timeline == "customer_by_time") {
                $product['date'] = $product->customer_buy_time;
            } else {
                $product['date'] = $product->created_at;
            }
            
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time']);
        }
    return $products;
    }
}
