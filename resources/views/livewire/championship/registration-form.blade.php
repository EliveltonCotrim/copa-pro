<div class="wrapper w-full h-14 md:max-w-5xl mx-auto pt-5 px-4">
    <div class="flex flex-col items-center p-4 space-y-4">
        <!-- Card -->
        <div class="w-full bg-white rounded-lg shadow-lg p-6">
            <!-- Header Image -->
            <div class="w-full h-48 bg-gray-200 rounded-md overflow-hidden flex items-center justify-center">
                <img src="{{ $championship->getFirstMediaUrl() }}" alt="Championship Image"
                    class="w-full h-full object-cover">
            </div>

            <!-- Description -->
            <div class="mt-8 p-6">
                <h2 class="text-4xl text-center font-extrabold text-indigo-600">{{ $championship->name }}</h2>
                <p class="text-gray-700 mt-4 text-lg leading-relaxed">{!! $championship->description !!}</p>
            </div>

            <div class="my-4" x-data="{ showForm: @entangle('showForm'), showVerificationForm: @entangle('showVerificationForm'), showInitForm: @entangle('showInitForm') }">
                <x-step wire:model.live="step" panels>
                    <x-step.items step="1" title="Inscrição" description="Informe os dados abaixo">
                        <!-- Form -->
                        <form class="mt-4 px-5 space-y-4">
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
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

                            <div x-show="showForm" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
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
                        </form>
                    </x-step.items>
                    <x-step.items step="2" title="Pagamento" description="Escolha a forma de pagamento">
                        <x-loading loading="createPayment()" />
                        <form class="mt-4 px-5 space-y-4">
                            <div class="grid grid-cols-1 gap-x-4">
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
                        </form>
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
    </script>
@endpush
