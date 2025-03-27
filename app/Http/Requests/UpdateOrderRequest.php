<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer' => ['string', 'max:255'],
            'products' => ['array'],
            'products.*.id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'products.*.count' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if(!$this->filled('customer') && !$this->filled('products')) {
                    $validator->errors()->add('update', 'Необходимо указать хотя бы 1 поле');
                }
            }
        ];
    }
}
