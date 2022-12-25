<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Range extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'property', 'warranty_time'];

    public function batch() {
        return $this->hasMany(Batch::class);
    }
}
