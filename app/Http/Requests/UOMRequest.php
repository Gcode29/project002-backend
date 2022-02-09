<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UOMRequest extends FormRequest
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
            'short_name' => [
                'required',
                Rule::unique('u_o_m_s')->ignore($this->uom),
            ],
            'long_name' => [
                'required',
                Rule::unique('u_o_m_s')->ignore($this->uom),
            ],
        ];
    }
}
