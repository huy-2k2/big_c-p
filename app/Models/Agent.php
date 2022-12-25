<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;

class Agent extends Model
{
    use HasFactory;
    protected $fillable = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
