<div
    class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8 flex justify-center items-start">
    <div class="max-w-7xl mx-auto my-auto">
        <div class="rounded-xl shadow-lg overflow-hidden">
            <!-- Borda superior em gradiente -->
            <div class="h-2 bg-gradient-to-r from-primary-400 to-primary/60"></div>
            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white rounded-b-xl overflow-hidden shadow-lg p-5">
                <!-- Left Column: Banner and Championship Info -->

                <div class="space-y-8">
                    <!-- Championship Header Section -->
                    <div class="relative rounded-2xl overflow-hidden shadow-sm">
                        <!-- Banner Image with overlay -->
                        <div class="w-full h-48 overflow-hidden">
                            <img src="{{ $championship->getFirstMediaUrl() }}" alt="Championship Image"
                                class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>

                        <!-- Championship Title Overlay -->
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h1 class="text-2xl md:text-3xl font-bold text-white">{{ $championship->name }}</h1>
                        </div>
                    </div>

                    <!-- Championship Info Section -->
                    <div class="space-y-6">
                        <!-- Description -->
                        <div class="prose prose-gray max-w-none">
                            <div class="text-base text-gray-700 leading-relaxed">{!! $championship->description !!}</div>
                        </div>

                        <!-- Championship Details - Improved horizontal layout -->
                        <div class="bg-white/70 backdrop-blur-sm rounded-xl p-6">
                            <div
                                class="flex flex-col justify-center sm:flex-row divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                                <!-- Championship Date -->
                                <div class="flex items-center py-3 sm:py-0 sm:px-4 sm:first:pl-0 sm:last:pr-0">
                                    <i data-lucide="calendar" class="h-5 w-5 text-gray-500 mr-3"></i>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Data</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $championship->start_date }}</p>
                                    </div>
                                </div>

                                <!-- Entry Fee -->
                                <div class="flex items-center py-3 sm:py-0 sm:px-4">
                                    <i data-lucide="credit-card" class="h-5 w-5 text-gray-500 mr-3"></i>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Taxa</p>
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $championship->getFeeFormatedAttribute() }}</p>
                                    </div>
                                </div>

                                <!-- Rules Link -->
                                <div class="flex items-center py-3 sm:py-0 sm:px-4">
                                    <i data-lucide="file-text" class="h-5 w-5 text-gray-500 mr-3"></i>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Regulamento</p>
                                        <a href="{{ $championship->regulation_path }}" target="_blank"
                                            class="text-sm font-medium text-teal-600 hover:text-teal-800 hover:underline transition-colors">
                                            Ver Regulamento
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Registration Steps Section - More modern, less card-like -->
                <div class="md:border-l border-gray-200 md:pl-8">
                    <!-- Registration Steps - Keeping the user's component -->
                    <div class="my-2">
                        <x-step wire:model.live="step" panels>
                            <x-step.items step="1" title="Dados" description="Informe os dados abaixo">
                                @if ($showFormGeral)
                                    <livewire:championship.registration.dados-gerais-form :$championship>
                                @endif
                            </x-step.items>
                            <x-step.items step="2" title="Pagamento" description="Escolha a forma de pagamento">
                                @if ($step === 2)
                                    <livewire:championship.registration.payment :$championship :$registrationForm
                                        :$player>
                                @endif
                            </x-step.items>
                        </x-step>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="pt-4 text-center">
            <p class="text-sm text-gray-500">
                © 2025 Championship Organization. All rights reserved.
            </p>
        </div>
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
