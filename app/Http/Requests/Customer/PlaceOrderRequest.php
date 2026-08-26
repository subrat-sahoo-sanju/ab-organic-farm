<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $codEnabled = setting('cod.enabled', '1') === '1';
        $maxCod = (float) setting('cod.max_order_value', 10000);

        return [
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
            'idempotency_token' => ['required', 'string', 'size:64'],
            'payment_method' => ['required', 'in:cod'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if (setting('cod.enabled', '1') !== '1') {
                $v->errors()->add('payment_method', 'Cash on Delivery is currently unavailable.');
            }
        });
    }
}
