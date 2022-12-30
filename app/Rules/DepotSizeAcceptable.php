<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class DepotSizeAcceptable implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $depot_id, $size, $status;
    public function __construct($depot_id, $size, $status)
    {
        $this->depot_id = $depot_id;
        $this->size = $size;
        $this->status = $status;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $current_size = Product::count_quantity_product(['depot_id', 'status_id'], [$this->depot_id, $this->status]);
        return $current_size <= $this->size;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'depot size is invalid';
    }
}
