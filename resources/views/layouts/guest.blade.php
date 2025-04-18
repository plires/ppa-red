<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplicación PPA RED') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- css y scripts -->
    @vite(['resources/css/public/app.css', 'resources/js/public/app.js', 'resources/css/app.css'])

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
