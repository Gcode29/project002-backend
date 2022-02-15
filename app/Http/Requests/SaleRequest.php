<?php

namespace App\Http\Requests;

use App\Rules\CheckAvailableQuantity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice' => ['sometimes', 'nullable'],
            'or_number' => ['sometimes', 'nullable'],
            'amount' => ['required'],
            'payment_method' => ['nullable'],
            // paid_at "DATENOW() if CASH"
            'paid_at' => ['nullable'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                Rule::exists('products', 'id'),
            ],
            'items.*.quantity' => ['required', 'numeric', 'min:1', new CheckAvailableQuantity($this->input('items.*.product_id'))],
            'items.*.price' => ['required', 'numeric', 'min:0.1'],
        ];
    }
}
