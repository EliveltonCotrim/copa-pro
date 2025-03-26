<div class="wrapper w-full md:max-w-3xl mx-auto pt-3 px-3">

    <div class="flex flex-col items-center p-4 space-y-4">
        <!-- Card -->
        <div class="w-full bg-white rounded-lg shadow-lg p-4">
            <!-- Header Image -->
            <div class="w-full h-32 bg-gray-200 rounded-md overflow-hidden flex items-center justify-center">
                <img src="{{ $championship->getFirstMediaUrl() }}" alt="Championship Image"
                    class="w-full h-full object-cover">
            </div>

            <!-- Description -->
            <div class="mt-2 p-2">
                <h2 class="text-3xl text-center font-extrabold text-indigo-600">{{ $championship->name }}</h2>
                <p class="text-gray-700 mt-2 text-lg leading-relaxed">{!! $championship->description !!}</p>
            </div>

            <div class="my-2">
                <x-step wire:model.live="step" panels>
                    <x-step.items step="1" title="Inscrição" description="Informe os dados abaixo">
                        <div class="mt-2 px-3 space-y-3">
                            @if ($showFormGeral)
                                <livewire:championship.registration.dados-gerais-form :$championship>
                            @endif
                        </div>
                    </x-step.items>
                    <x-step.items step="2" title="Pagamento" description="Escolha a forma de pagamento">
                        @if ($step === 2)
                            <livewire:championship.registration.payment :$championship :$registrationForm :$player>
                        @endif
                    </x-step.items>
                </x-step>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function cpfCnpjMask(input) {
            value = input.replace(/\D/g, ""); // Remove tudo o que não é dígito

            if (value.length <= 11) {
                //CPF
                value = value.replace(/(\d{3})(\d)/, "$1.$2");
                value = value.replace(/(\d{3})(\d)/, "$1.$2");
                value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            } else if (value.length > 11 && value.length <= 18) {
                // CNPJ: 00.000.000/0000-00
                value = value.replace(/^(\d{2})(\d)/, "$1.$2");
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
                value = value.replace(/\.(\d{3})(\d)/, ".$1/$2");
                value = value.replace(/(\d{4})(\d{1,2})$/, "$1-$2");
            }

            return value;
        }

        function copyQRCode() {
            const qrInput = document.getElementById('qrCodeInput');

            qrInput.select();
            qrInput.setSelectionRange(0, 99999); /* For mobile devices */
            navigator.clipboard.writeText(qrInput.value);

            alert('Código copiado!');
        }
    </script>
@endpush
