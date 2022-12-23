<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id', 'owner_name', 'size', 'status'];

    public function product() {
        return $this->hasMany(Product::class);
    }
}
