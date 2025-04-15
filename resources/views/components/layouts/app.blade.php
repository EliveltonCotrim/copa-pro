<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <tallstackui:script />
    @filamentStyles
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

</head>

<body class="antialiased" style="background-image: url({{ asset('images/background-login-filter-black.webp') }});">

    <x-toast />

    {{ $slot }}

    @livewireScripts

    @stack('scripts')
</body>

</html>
