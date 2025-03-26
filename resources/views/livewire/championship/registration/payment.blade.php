<div x-data="{ isCpfFormVisible: @entangle('isCpfFormVisible') }">
    <x-loading loading="createPayment" />

    <div x-show="isCpfFormVisible" class="mt-2 px-3 space-y-3" x-transition>
        <div class="grid grid-cols-1 gap-x-3">
            <div class="mb-3">
                <x-input label="CPF ou CNPJ *" placeholder="Informe seu CPF ou CPNJ" wire:model="form.cpf_cnpj"
                    x-mask:dynamic="cpfCnpjMask" maxlength="18" required />
            </div>
        </div>
        <div x-transition class="mt-2 grid grid-cols-1">
            <x-button sm icon="qr-code" position="right" text="Gerar QR Code" wire:click="createPayment()" />
        </div>
    </div>
    <div x-show="!isCpfFormVisible" x-transition>
        @if (!$isCpfFormVisible)
            <div wire:poll.4000ms="checkPayment"></div>
            <x-payment.card-pix :qrCode64="$playerCharge->qr_code_64" :qrCode="$playerCharge->qr_code" :price="$championship->registration_fee" />
        @endif
    </div>
</div>
