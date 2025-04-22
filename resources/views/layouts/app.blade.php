<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Administración PPA RED') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- css -->
    @vite(['resources/css/app.css', 'resources/css/imports.css', 'resources/js/app.js'])

    @yield('css') <!-- Sección específica para CSS extra -->
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('parts.nav_admin')

        @include('parts.aside_admin')

        <main>
            {{ $slot }}
        </main>

        @include('parts.footer_admin')

    </div>

    @yield('scripts') <!-- Sección específica para JS extra -->
</body>
