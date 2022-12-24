<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['depot_id', 'batch_id', 'agent_id', 'customer_id', 'factory_id', 'status', 'warranty_id'];


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
