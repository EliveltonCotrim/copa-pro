<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsaasWebHookRequest extends FormRequest
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
            'id' => 'required|string',
            'event' => 'required|string',
            'dateCreated' => 'required|string',
            'payment' => 'required|array',
            'payment.id' => 'required|string',
            'payment.status' => 'required|string',
            'payment.customer' => 'required|string',
            'payment.value' => 'required|numeric',
            'payment.netValue' => 'required|numeric',
            'payment.confirmedDate' => 'required|string',
            'payment.paymentDate' => 'required|string',
            'payment.billingType' => 'required|string',
            'payment.transactionReceiptUrl' => 'required|string',
        ];
    }
}
