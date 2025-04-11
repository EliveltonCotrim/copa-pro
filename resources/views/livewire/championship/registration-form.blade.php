<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4">
    <div class="wrapper w-full md:max-w-3xl mx-auto">
        <!-- Main Card -->
        <div class="bg-white rounded-xl overflow-hidden shadow-lg">
            <!-- Championship Header Section -->
            <div class="relative">
                <!-- Banner Image with overlay -->
                <div class="w-full h-32 overflow-hidden">
                    <img src="{{ $championship->getFirstMediaUrl() }}" alt="Championship Image"
                        class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>
            </div>

            <!-- Championship Info Section -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">{{ $championship->name }}</h3>
                </div>

                <div class="space-y-5">
                    <!-- Description -->
                    <div class="flex items-start group">
                        <div class="text-base text-gray-700 leading-relaxed">{!! $championship->description !!}</div>
                    </div>

                    <!-- Championship Details - Compact Layout -->
                    <div class="p-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <!-- Championship Date -->
                            <div class="flex items-center">
                                <i data-lucide="calendar" class="h-4 w-4 text-gray-500 mr-2"></i>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Date</p>
                                    <p class="text-sm text-gray-700">{{ $championship->start_date }}</p>
                                </div>
                            </div>

                            <!-- Entry Fee -->
                            <div class="flex items-center">
                                <i data-lucide="credit-card" class="h-4 w-4 text-gray-500 mr-2"></i>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Taxa</p>
                                    <p class="text-sm text-gray-700">{{ $championship->getFeeFormatedAttribute() }}</p>
                                </div>
                            </div>

                            <!-- Rules Link -->
                            <div class="flex items-center">
                                <i data-lucide="file-text" class="h-4 w-4 text-gray-500 mr-2"></i>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Regulation</p>
                                    <a href="/championships/{{ $championship->id }}/regulations"
                                        class="text-sm text-teal-600 hover:text-teal-800 font-medium hover:underline transition-colors">
                                        View Regulations
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Steps Section -->
            <div class="p-6">
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

        <!-- Footer -->
        <p class="text-center text-xs text-gray-500 pt-4">
            © 2025 Championship Organization. All rights reserved.
        </p>
    </div>
</div>

<!-- Include Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Initialize Lucide Icons
    lucide.createIcons();

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
