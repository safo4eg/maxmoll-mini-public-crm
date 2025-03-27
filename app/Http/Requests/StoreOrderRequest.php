<?php

namespace App\Http\Requests;

use App\Enums\OrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
            'customer' => ['required', 'string', 'max:255'],
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'status' => ['required', 'string', 'max:255', Rule::in(OrderStatusEnum::getValues())],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'products.*.count' => ['required', 'integer', 'min:1'],
        ];
    }
}
