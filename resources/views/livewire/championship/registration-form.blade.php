<div class="wrapper w-full md:max-w-3xl mx-auto pt-3 px-3">
    <x-loading />

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

            <div class="my-2" x-data="{
                showForm: @entangle('showForm'),
                showVerificationForm: @entangle('showVerificationForm'),
                showInitForm: @entangle('showInitForm'),
                showPaymentForm: @entangle('showPaymentForm')
            }">
                <x-step wire:model.live="step" panels>
                    <x-step.items step="1" title="Inscrição" description="Informe os dados abaixo">
                        <!-- Form -->
                        <div class="mt-2 px-3 space-y-3">
                            <div x-show="showInitForm" x-transition>
                                <div class="mb-3">
                                    <x-alert
                                        text="Use o mesmo nickname ou e-mail das inscrições anteriores. Se for a primeira vez, cadastre um novo."
                                        light />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                                    <div class="mb-3">
                                        <x-input label="NickName" wire:model="registrationForm.nickname" />
                                    </div>
                                    <div class="mb-3">
                                        <x-input label="E-mail" wire:model="registrationForm.email" />
                                    </div>
                                </div>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-button sm icon="magnifying-glass" text="Pesquisar" wire:click="searchPlayer" />
                                </div>
                            </div>
                            <div x-show="showVerificationForm" x-transition>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-3">
                                    <div class="mb-3">
                                        <x-pin length="5" label="Código"
                                            wire:model="registrationForm.verification_code"
                                            hint="Enviamos um código de 5 dígitos para seu e-mail." />
                                    </div>
                                </div>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-button sm icon="check" text="Validar código" wire:click="verifyCode" />
                                </div>
                            </div>
                            <div x-show="showForm" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-x-3">
                                <div class="mb-3">
                                    <x-input label="Nome *" placeholder="Informe seu nome completo"
                                        wire:model="registrationForm.name" required />
                                </div>
                                <div class="mb-3">
                                    <x-input label="E-mail *" type="email" wire:model="registrationForm.email"
                                        required />
                                </div>
                                <div class="mb-3">
                                    <x-input label="Nickname *" wire:model="registrationForm.nickname" required />
                                </div>
                                <div class="mb-3">
                                    <x-select.styled label="Genêro" placeholder="Selecione um gênero"
                                        wire:model="registrationForm.sex" :options="$genders"
                                        select="label:label|value:value" />
                                </div>
                                <div class="mb-3">
                                    <x-date label="Data de nascimento" :max-date="now()"
                                        wire:model="registrationForm.birth_dt" format="DD/MM/YYYY"
                                        placeholder="00/00/0000" />
                                </div>
                                <div class="mb-3">
                                    <x-input icon="phone" label="Whatsapp *" wire:model="registrationForm.phone"
                                        x-mask="(99) 99999-9999" placeholder="(00) 00000-0000" required />
                                </div>
                                <div class="mb-3">
                                    <x-input label="Time do coração" wire:model="registrationForm.heart_team_name" />
                                </div>
                                <div class="mb-3">
                                    <x-input label="Time do Campeonato *"
                                        wire:model="registrationForm.championship_team_name" required />
                                </div>
                                <div class="mb-3">
                                    <x-select.styled label="Plataforma de Jogo *" placeholder="Selecione uma plataforma"
                                        wire:model="registrationForm.game_platform" :options="$gammingPlatforms"
                                        select="label:label|value:value" />
                                </div>
                                <div class="mb-3">
                                    <x-select.styled label="Nível de Experiência"
                                        placeholder="Selecione seu nível de experência"
                                        wire:model="registrationForm.level_experience" :options="$experienceLevels"
                                        select="label:label|value:value" />
                                </div>
                            </div>
                            <div x-show="showForm" x-transition class="mt-2 grid grid-cols-1">
                                <x-button sm x-show="showForm" icon="chevron-right" position="right" text="Avançar"
                                    wire:click="nextStepControl(2)" />
                            </div>
                        </div>
                    </x-step.items>
                    <x-step.items step="2" title="Pagamento" description="Escolha a forma de pagamento">
                        <div x-show="showPaymentForm" class="mt-2 px-3 space-y-3" x-transition>
                            <div class="grid grid-cols-1 gap-x-3">
                                <div class="mb-3">
                                    <x-input label="CPF ou CNPJ *" placeholder="Informe seu CPF ou CPNJ"
                                        wire:model="registrationForm.cpf_cnpj" x-mask:dynamic="cpfCnpjMask"
                                        maxlength="18" required />
                                </div>
                            </div>
                            <div x-show="showForm" x-transition class="mt-2 grid grid-cols-1">
                                <x-button sm x-show="showForm" icon="qr-code" position="right" text="Gerar QRCode"
                                    wire:click="createPayment()" />
                            </div>
                        </div>
                        <div x-show="!showPaymentForm" x-transition>
                            <div class="px-6 py-4 space-y-6 bg-gray-50 rounded-lg shadow-sm">
                                <!-- QR Code + Valor + Data de Validade -->
                                <div class="px-6 py-2 flex flex-col items-center text-center space-y-3">
                                    <img src="data:image/jpeg;base64,{{ $playerCharge->qr_code_64 ?? '' }}"
                                        class="border border-2 rounded-lg p-2 border-primary-300 w-[140px] shadow-lg">
                                    <div class="flex items-center justify-center divide-x">
                                        <div class="p-1">
                                            <p class="text-gray-600 text-sm">Valor do Pix</p>
                                            <p class="text-1xl font-bold text-green-500">R$
                                                {{ $championship->registration_fee }}</p>
                                        </div>

                                        {{-- <div class="p-2">
                                                <p class="text-gray-600 text-sm">Válido até</p>
                                                <p class="text-2xl font-semibold text-red-500">
                                                    1-1-2022
                                                </p>
                                            </div> --}}
                                    </div>

                                    <!-- Campo com botão de copiar -->
                                    <div class="relative w-full max-w-sm">
                                        <input type="text" id="qrCodeInput"
                                            class="w-full border border-gray-300 rounded-lg py-2 px-3 pr-12 text-gray-700 bg-gray-100"
                                            value="{{ $playerCharge->qr_code ?? '' }}" readonly>
                                        <button onclick="copyQRCode()"
                                            class="absolute right-1 top-1/2 -translate-y-1/2 bg-primary-600 text-white px-3 py-1.5 rounded-md hover:bg-primary-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <!-- Instruções de Pagamento -->
                                <div class="p-2 !mt-0">
                                    <h3 class="font-bold text-lg text-gray-800 mt-0 mb-4 text-center">Como pagar?
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="flex items-center justify-center w-8 h-8 bg-primary-500 text-white rounded-full text-lg font-bold">
                                                1
                                            </span>
                                            <p class="text-gray-700 text-sm">
                                                Entre no app ou site do seu banco e escolha a opção de pagamento via
                                                Pix.
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="flex items-center justify-center w-8 h-8 bg-primary-500 text-white rounded-full text-lg font-bold">
                                                2
                                            </span>
                                            <p class="text-gray-700 text-sm">
                                                Escaneie o código QR ou copie e cole o código de pagamento.
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="flex items-center justify-center w-8 h-8 bg-primary-500 text-white rounded-full text-lg font-bold">
                                                3
                                            </span>
                                            <p class="text-gray-700 text-sm">
                                                Pronto! O pagamento será creditado na hora e você receberá um e-mail
                                                de
                                                confirmação.
                                            </p>
                                        </div>
                                    </div>

                                    <p class="text-xs text-gray-600 mt-3 text-center">
                                        O Pix tem um limite diário de transferências. Para mais informações,
                                        consulte
                                        seu banco.
                                    </p>
                                </div>
                            </div>
                        </div>
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
