<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingProduct extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'range_id', 'transfer_batch', 'status'];

}
