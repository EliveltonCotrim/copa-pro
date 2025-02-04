<div class="wrapper w-full md:max-w-5xl mx-auto pt-20 px-4">
    <div class="flex flex-col items-center p-4 space-y-4">
        <!-- Steps -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-8 sm:space-y-0">
            <!-- Step 1 -->
            <div class="flex items-center space-x-4 w-full sm:w-auto">
                <div
                    class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-500 text-white border-2 border-blue-500">
                    1
                </div>
                <div>
                    <p class="font-semibold text-blue-500">Inscrição</p>
                    <p class="text-sm text-gray-500">Informe os dados abaixo</p>
                </div>
            </div>

            <!-- Separator 1 -->
            <div class="hidden sm:flex flex-1 items-center px-4">
                <div class="h-1 w-full bg-gray-300"></div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-center space-x-4 w-full sm:w-auto">
                <div
                    class="flex items-center justify-center w-12 h-12 rounded-full bg-white text-gray-400 border-2 border-gray-300">
                    2
                </div>
                <div>
                    <p class="font-semibold text-gray-700">Pagamento</p>
                    <p class="text-sm text-gray-500">Escolha a forma de pagamento</p>
                </div>
            </div>

            <!-- Separator 2 -->
            <div class="hidden sm:flex flex-1 items-center px-4">
                <div class="h-1 w-full bg-gray-300"></div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-center space-x-4 w-full sm:w-auto">
                <div
                    class="flex items-center justify-center w-12 h-12 rounded-full bg-white text-gray-400 border-2 border-gray-300">
                    3
                </div>
                <div>
                    <p class="font-semibold text-gray-700">Confirmação</p>
                    <p class="text-sm text-gray-500">Revise suas informações</p>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="w-full bg-white rounded-lg shadow-lg p-6">
            <!-- Header Image -->
            <div class="w-full h-40 bg-gray-200 rounded-md overflow-hidden">
                <img src="{{ asset('images/background-login-filter-black.webp') }}" alt="Championship Image"
                    class="w-full h-full object-cover">
            </div>

            <!-- Description -->
            <div class="mt-4">
                <h2 class="text-xl font-bold">Championship Title</h2>
                <p class="text-gray-600 mt-2">
                    This is a brief description of the championship, highlighting key details
                    and excitement.</p>
            </div>

            <!-- Form -->
            <form class="mt-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="mb-3">
                        <x-input label="Nome" placeholder="Nome completo" required />
                    </div>
                    <div class="mb-3">
                        <x-input type="date" label="Data de nascimento" />
                    </div>
                    <div class="mb-3">
                        <x-input label="E-mail" type="email" />
                    </div>
                    <div class="mb-3">
                        <x-input icon="phone" label="Whatsapp" />
                    </div>
                    <div class="mb-3">
                        <x-input label="Time do Campeonato" />
                    </div>
                    <div class="mb-3">
                        <x-input label="Time do coração" />
                    </div>
                    <div class="mb-3">
                        <x-select label="Plataforma de Jogo" placeholder="Selecione uma plataforma" :options="App\Enum\PlayerPlatformGameEnum::options()" />
                    </div>
                    <div class="mb-3">
                        <x-select label="Genêro" placeholder="Selecione um gênero" :options="App\Enum\PlayerSexEnum::options()" />
                    </div>
                    <div class="mb-3">
                        <x-select label="Nível de Experiência" placeholder="Selecione seu nível de experência"
                            :options="App\Enum\PlayerExperienceLevelEnum::options()" />
                    </div>
                </div>
                <x-button primary label="Primary" />
            </form>
        </div>
    </div>

    {{-- <form wire:submit="save">
        {{ $this->form }}

    </form> --}}
</div>
