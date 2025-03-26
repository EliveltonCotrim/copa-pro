@props(['qrCode64', 'qrCode', 'price', 'dueDate' => null])

<div {{ $attributes }} class="px-6 py-4 space-y-6 bg-gray-50 rounded-lg shadow-sm">
    <!-- QR Code + Valor + Data de Validade -->
    <div class="px-6 py-2 flex flex-col items-center text-center space-y-3">
        <img src="data:image/jpeg;base64,{{ $qrCode64 ?? '' }}"
            class="border border-2 rounded-lg p-1 border-primary-300 w-[140px] shadow-lg">
        <div class="flex items-center justify-center divide-x">
            <div class="p-1">
                <p class="text-gray-600 text-sm">Valor do Pix</p>
                <p class="text-1xl font-bold text-green-500">R$
                    {{ $price }}</p>
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
                value="{{ $qrCode }}" readonly>
            <button onclick="copyQRCode()"
                class="absolute right-1 top-1/2 -translate-y-1/2 bg-primary-600 text-white px-3 py-1.5 rounded-md hover:bg-primary-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
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

@push('scripts')
    <script>
        function copyQRCode() {
            const qrInput = document.getElementById('qrCodeInput');

            qrInput.select();
            qrInput.setSelectionRange(0, 99999); /* For mobile devices */
            navigator.clipboard.writeText(qrInput.value);

            alert('Código copiado!');
        }
    </script>
@endpush
