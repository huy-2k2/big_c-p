<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'quantity', 'date', 'range_id', 'factory_id'];


    public function range()
    {
        return $this->belongsTo(Range::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'user_id');
    }

    public static function get_recall($is_recall = 1)
    {
        $batches_id = Product::where('is_recall', $is_recall)->get('batch_id');
        return Self::whereIn('id', $batches_id)->get();
    }
}
