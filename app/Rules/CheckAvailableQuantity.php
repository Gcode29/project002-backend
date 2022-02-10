<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class CheckAvailableQuantity implements Rule
{
    protected array $ids;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
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
        $index = explode('.', $attribute)[1];

        $product = Product::withSum('transactions as stocks', 'quantity')->find($this->ids[$index]);

        if ($product === null) {
            return false;
        }

        return $product->stocks >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute quantity is not available.';
    }
}
