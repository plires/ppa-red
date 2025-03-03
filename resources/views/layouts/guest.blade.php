<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplicación PPA RED') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- css y scripts -->
    @vite(['resources/css/public/app.css', 'resources/js/public/app.js'])

    @yield('css') <!-- Sección específica para CSS extra -->
</head>

@php
    $bodyClass = match (Route::currentRouteName()) {
        'login' => 'login-page',
        'password.request' => 'lockscreen',
        default => '',
    };
@endphp

<body class="{{ $bodyClass }}">
    {{ $slot }}

    @yield('scripts') <!-- Sección específica para JS extra -->
</body>

</html>
