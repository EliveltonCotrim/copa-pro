@props(['qrCode64', 'qrCode', 'price', 'dueDate' => null])

<div {{ $attributes }} class="bg-white/70 backdrop-blur-sm rounded-xl overflow-hidden">
    <!-- Main Container - Flex Row Layout -->
    <div class="flex flex-col md:flex-row">
        <!-- Left Side - QR Code -->
        <div class="p-6 flex flex-col items-center justify-center text-center space-y-4 md:w-2/5">
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                <img src="data:image/jpeg;base64,{{ $qrCode64 ?? '' }}" class="w-[160px] h-[160px]">
            </div>

            <div class="flex items-center justify-center">
                <div class="px-3 py-2">
                    <p class="text-gray-500 text-xs font-medium">Valor do Pix</p>
                    <p class="text-xl font-bold text-green-600">{{ $price }}</p>
                </div>

                @if ($dueDate)
                    <div class="px-3 py-2 border-l border-gray-200">
                        <p class="text-gray-500 text-xs font-medium">Válido até</p>
                        <p class="text-sm font-medium text-gray-800">{{ $dueDate }}</p>
                    </div>
                @endif
            </div>

            <!-- Campo com botão de copiar -->
            <div class="relative w-full max-w-sm mt-2">
                <input type="text" id="qrCodeInput"
                    class="w-full border border-gray-200 rounded-lg py-2.5 px-3 pr-12 text-gray-700 bg-white/80 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                    value="{{ $qrCode }}" readonly>
                <button onclick="copyQRCode()"
                    class="absolute right-1.5 top-1/2 -translate-y-1/2 bg-primary-600 text-white p-1.5 rounded-md hover:bg-primary-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Right Side - Payment Instructions -->
        <div class="p-6 md:w-3/5 md:border-l md:border-gray-200">
            <h3 class="font-bold text-lg text-gray-800 mb-5 text-center md:text-left">
                Como pagar?
            </h3>
            <div class="space-y-5">
                <div class="flex">
                    <div class="flex-shrink-0 mr-4">
                        <span
                            class="flex items-center justify-center w-8 h-8 bg-primary-600 text-white rounded-full text-sm font-medium shadow-sm">
                            1
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            Entre no app ou site do seu banco e escolha a opção de pagamento via Pix.
                        </p>
                    </div>
                </div>

                <div class="flex">
                    <div class="flex-shrink-0 mr-4">
                        <span
                            class="flex items-center justify-center w-8 h-8 bg-primary-600 text-white rounded-full text-sm font-medium shadow-sm">
                            2
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            Escaneie o código QR ou copie e cole o código de pagamento.
                        </p>
                    </div>
                </div>

                <div class="flex">
                    <div class="flex-shrink-0 mr-4">
                        <span
                            class="flex items-center justify-center w-8 h-8 bg-primary-600 text-white rounded-full text-sm font-medium shadow-sm">
                            3
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            Pronto! O pagamento será creditado na hora e você receberá um e-mail de confirmação.
                        </p>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-5 text-center md:text-left">
                O Pix tem um limite diário de transferências. Para mais informações, consulte seu banco.
            </p>
            <p class="text-xs text-red-500 mt-1 text-center md:text-left font-semibold">
                Atenção: caso o pagamento não seja efetuado em até 15 minutos, a inscrição será automaticamente
                cancelada.
            </p>
        </div>
    </div>
</div>
