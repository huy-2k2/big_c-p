<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

    public function product() {
        return $this->hasMany(Product::class);
    }

    public static function check_customer_exist($customer_id) {
        $check = DB::table('customers')->where('user_id', $customer_id)->first();
        if($check) {
            return true;
        } else {
            return false;
        }
    }
}
