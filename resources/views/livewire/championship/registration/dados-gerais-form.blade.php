<div x-data="{
    showVerificationForm: @entangle('showVerificationForm'),
    showSearchPlayerForm: @entangle('showSearchPlayerForm'),
}">
    <x-loading loading="verifyCode, searchPlayer" />

    <div x-show="showSearchPlayerForm" x-transition>
        <div class="mb-3">
            <x-alert
                text="Use o mesmo e-mail das inscrições anteriores.
                Caso seja sua primeira vez, basta informar um e-mail e clicar em 'Prosseguir'"
                light />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-x-4">
            <div class="mb-3">
                <x-input label="E-mail" wire:model="registrationForm.email" />
            </div>
        </div>
        <div class="mt-2 grid grid-cols-1">
            <x-button sm icon="chevron-double-right" text="Prosseguir" wire:click="searchPlayer" />
        </div>
    </div>
    <div x-show="showVerificationForm" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-3">
            <div class="mb-3">
                <x-pin length="5" label="Código" wire:model="registrationForm.verification_code"
                    hint="Enviamos um código de 5 dígitos para seu e-mail." />
            </div>
        </div>
        <div class="mt-2 grid grid-cols-1">
            <x-button sm icon="check" text="Validar código" wire:click="verifyCode" />
        </div>
    </div>
    <div x-show="showSearchPlayerForm === false && showVerificationForm === false" x-transition
        class="grid grid-cols-1 md:grid-cols-2 gap-x-3">
        <div class="mb-3">
            <x-input label="Nome *" placeholder="Informe seu nome completo" wire:model="registrationForm.name"
                required />
        </div>
        <div class="mb-3">
            <x-input label="E-mail *" type="email" wire:model="registrationForm.email" required />
        </div>
        <div class="mb-3">
            <x-input label="Nickname *" wire:model="registrationForm.nickname" required />
        </div>
        <div class="mb-3">
            <x-select.styled label="Genêro" placeholder="Selecione um gênero" wire:model="registrationForm.sex"
                :options="$genders" select="label:label|value:value" />
        </div>
        <div class="mb-3">
            <x-date label="Data de nascimento" :max-date="now()" wire:model="registrationForm.birth_dt"
                format="DD/MM/YYYY" placeholder="00/00/0000" />
        </div>
        <div class="mb-3">
            <x-input icon="phone" label="Whatsapp *" wire:model="registrationForm.phone" x-mask="(99) 99999-9999"
                placeholder="(00) 00000-0000" required />
        </div>
        <div class="mb-3">
            <x-input label="Time do coração" wire:model="registrationForm.heart_team_name" />
        </div>
        <div class="mb-3">
            <x-input label="Time do Campeonato *" wire:model="registrationForm.championship_team_name" required />
        </div>
        <div class="mb-3">
            <x-select.styled label="Plataforma de Jogo *" placeholder="Selecione uma plataforma"
                wire:model="registrationForm.game_platform" :options="$gammingPlatforms" select="label:label|value:value" />
        </div>
        <div class="mb-3">
            <x-select.styled label="Nível de Experiência" placeholder="Selecione seu nível de experência"
                wire:model="registrationForm.level_experience" :options="$experienceLevels" select="label:label|value:value" />
        </div>
    </div>
    <div x-show="showSearchPlayerForm === false && showVerificationForm === false" x-transition
        class="mt-2 grid grid-cols-1">
        <x-button sm icon="chevron-right" position="right" text="Avançar" wire:click="nextStep(2)" />
    </div>
</div>
