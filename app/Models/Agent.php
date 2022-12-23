<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;

class Agent extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function agent_customer_product() {
        return $this->hasMany(AgentCustomerProduct::class);
    }

    public function agent_product_warranter() {
        return $this->hasMany(Models\AgentProductWarranter::class);
    }

    public function depot() {
        return $this->morphMany(Models\Depot::class, 'depotable');
    }
}
