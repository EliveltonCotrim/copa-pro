<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class PaymentPixForm extends Form
{
    public string $cpf_cnpj = '054.932.825-42';

    public ?string $customer;

    public string $billingType;

    public string $value;

    public string $dueDate;

    public function rules()
    {
        return [
            'cpf_cnpj' => 'required|string|max:18|formato_cpf_ou_cnpj',
        ];
    }
}
