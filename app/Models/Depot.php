<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    protected $fillable = ['depot_name', 'owner_id', 'size', 'status'];

    public function product() {
        return $this->hasMany(Product::class);
    }
}
