<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    protected $fillable = ['depot_name', 'owner_id', 'size', 'status'];

    public static function depot_check_have($owner_id, $depot_id) {
        $current_depots = DB::table('depots')->where('owner_id', '=', $owner_id)->get();
            
            foreach($current_depots as $depot) {
                if($depot->id == $depot_id) {
                    return true;
                }
            }
        return false;
    }

    public static function depot_check_still_empty($depot_id, $quantity_input) {
        $count_quantity_prod_in_depot = Product::count_quantity_product(['depot_id'], [$depot_id]);
        $size_depot = (DB::table('depots')->where('id', '=', $depot_id)->first())->size;
        if($quantity_input + $count_quantity_prod_in_depot > $size_depot) {
            return false;
        }
        return true;
    }

    public function product() {
        return $this->hasMany(Product::class);
    }
}
