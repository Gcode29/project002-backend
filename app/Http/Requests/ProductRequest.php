<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            'category_id' => [
                'required',
                Rule::exists('categories', 'id'),
            ],
            'brand_id' => [
                'required',
                Rule::exists('brands', 'id'),
            ],
            'u_o_m_id' => [
                'required',
                Rule::exists('u_o_m_s', 'id'),
            ],
            // make uniquename unique products table
            'unique_name' => [
                'required|unique',
                Rule::unique('products')->ignore($this->product),
            ],
            'description' => ['sometimes', 'nullable'],
        ];
    }
}
