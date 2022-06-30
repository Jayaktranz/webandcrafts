<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'customer_fname' => 'required|string|max:25',
            'customer_lname' => 'required|string|max:25',
            'phone_number' => 'required|numeric|digits:10',
            'product_lists' => 'required|array',
            'product_lists.*' => 'exists:products,id',
        ];
    }

    public function messages()
    {
        return [
            'phone_number.numeric' => 'Please provide valid 10 digit number.',
            'phone_number.digits' => 'Please provide valid 10 digits number',
        ];
    }
}
