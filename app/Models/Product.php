<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['depot_id', 'batch_id', 'agent_id', 'customer_id', 'factory_id', 'status', 'warranty_id'];

    public static function count_quantity_product($names, $id_values) {
        $count_result = DB::table('products')->where(function($query) use ($names,$id_values) {
            for($i = 0; $i < count($names); $i++) {
                $query->where($names[$i], '=', $id_values[$i]);
            }
        })->get()->count();

        return $count_result;
    }

    public static function get_product($names, $id_values) {
        $result = DB::table('products')->where(function($query) use ($names,$id_values) {
            for($i = 0; $i < count($names); $i++) {
                $query->where($names[$i], '=', $id_values[$i]);
            }
        })->get();
        
        return $result;
    }


    public function batch()
    {
        return $this->belongsTo(Batch::class);
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
}
