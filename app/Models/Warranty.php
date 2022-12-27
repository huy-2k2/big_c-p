<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;
    protected $fillable = ['id'];

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
    
    public static function check_warranty_exist($warranty_id) {
        $check = DB::table('warranties')->where('user_id', $warranty_id)->first();
        if($check) {
            return true;
        } else {
            return false;
        }
    }
}
