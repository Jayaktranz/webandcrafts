<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
            'name'          => 'required|string|unique:products,name',
            'image'         => 'required|mimes:jpeg,png,jpg,svg',
            'category_id'   => 'required|exists:categories,id',
            'price'   => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $product = $this->route()->parameter('product');
            $rules['name'] = 'required|string|unique:products,name,'.$product;
            $rules['image'] = 'nullable|mimes:jpeg,png,jpg,svg';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'category_id.required' => 'The category is required',
            'category_id.exists' => 'The category is invalid',
        ];
    }
}
