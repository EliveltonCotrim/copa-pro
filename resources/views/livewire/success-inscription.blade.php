<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col items-center justify-center p-6">
    <div class="max-w-4xl w-full">
        <!-- Main Content -->
        <div class="bg-white rounded-xl overflow-hidden shadow-lg">
            <div class="h-2 bg-gradient-to-r from-green-400 to-teal-500"></div>
            <div class="md:flex">
                <!-- Left Column with Success Icon -->
                <div class="md:w-1/4 p-6 flex flex-col items-center justify-center bg-gray-50">
                    <!-- Success Icon with enhanced animation -->
                    <div class="flex justify-center mb-4">
                        <div class="rounded-full bg-green-100 p-4 shadow-md opacity-0"
                            style="animation: fadeIn 0.6s ease-out forwards, bounce 0.5s ease-out 0.6s;">
                            <i data-lucide="check" class="h-12 w-12 text-green-500 opacity-0 scale-0"
                                style="animation: scaleIn 0.3s ease-out 0.7s forwards, pulse 2s ease-in-out 1.2s infinite; stroke-width: 2.5;"></i>
                        </div>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800 text-center">
                        Registration Successful
                    </h1>
                </div>

                <!-- Right Column with Details -->
                <div class="md:w-3/4 p-6">
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-800">Championship Details</h2>
                                <span
                                    class="ml-3 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Confirmed</span>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="flex items-start group">
                                    <div
                                        class="flex-shrink-0 mt-1 p-2 rounded-lg bg-gray-50 group-hover:bg-gray-100 transition-colors">
                                        <i data-lucide="file-text" class="h-5 w-5 text-gray-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Title</p>
                                        <p class="text-base text-gray-800 font-medium">2025 National Swimming
                                            Championship</p>
                                    </div>
                                </div>

                                <div class="flex items-start group">
                                    <div
                                        class="flex-shrink-0 mt-1 p-2 rounded-lg bg-gray-50 group-hover:bg-gray-100 transition-colors">
                                        <i data-lucide="calendar" class="h-5 w-5 text-gray-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Start Date</p>
                                        <p class="text-base text-gray-800 font-medium">June 15, 2025</p>
                                    </div>
                                </div>

                                <div class="flex items-start group md:col-span-2">
                                    <div
                                        class="flex-shrink-0 mt-1 p-2 rounded-lg bg-gray-50 group-hover:bg-gray-100 transition-colors">
                                        <i data-lucide="file-text" class="h-5 w-5 text-gray-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Regulation</p>
                                        <a href="/regulations/championship-2025.pdf"
                                            class="text-base text-teal-600 hover:text-teal-800 font-medium hover:underline transition-colors">
                                            View Championship Regulations
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-teal-50 p-4 rounded-xl border border-blue-100">
                            <p class="text-gray-800 font-medium mb-1">
                                <i data-lucide="mail" class="inline h-4 w-4 mr-2 text-blue-500"></i>
                                Email Confirmation
                            </p>
                            <p class="text-gray-600 text-sm">A confirmation of your payment has been sent to your email.
                            </p>
                            <p class="text-gray-600 text-sm mt-1">
                                You will also receive an email with all the championship details shortly.
                            </p>
                        </div>

                        <div class="pt-1">
                            <h3 class="text-base font-medium text-gray-800 mb-2">Support Contact</h3>
                            <div class="grid md:grid-cols-2 gap-2">
                                <a href="mailto:support@championship.com"
                                    class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                                    <div
                                        class="p-1.5 rounded-full bg-teal-50 group-hover:bg-teal-100 transition-colors">
                                        <i data-lucide="mail" class="h-4 w-4 text-teal-600"></i>
                                    </div>
                                    <span
                                        class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">
                                        support@championship.com
                                    </span>
                                </a>

                                <a href="tel:+15551234567"
                                    class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                                    <div
                                        class="p-1.5 rounded-full bg-teal-50 group-hover:bg-teal-100 transition-colors">
                                        <i data-lucide="phone" class="h-4 w-4 text-teal-600"></i>
                                    </div>
                                    <span
                                        class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">
                                        +1 (555) 123-4567
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-500 pt-4">
            © 2025 Championship Organization. All rights reserved.
        </p>
    </div>
</div>

@push('styles')
    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes scaleIn {
            0% {
                opacity: 0;
                transform: scale(0);
            }

            70% {
                opacity: 1;
                transform: scale(1.2);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
@endpush
