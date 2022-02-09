<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'client_name' => [
                'required'
            ],
            'address' => [
                'sometimes',
                'nullable',
            ],
            'contact' => [
                'sometimes',
                'nullable',
            ],
            'business_name' => [
                'required',
                Rule::unique('clients')->ignore($this->client),
            ]
        ];
    }
}
