<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <link rel="icon" href="{{ asset('images/favicon.png') }}">

    <title>{{ $title ?? (View::hasSection('title') ? View::getSection('title') : 'Dashboard') }} — {{ config('app.name', 'K-Clean') }}</title>

 <!-- Fonts -->
 <link rel="preconnect" href="https://fonts.bunny.net">
 <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <x-theme-script />
    <style>
        body {
            transition: background-color 0.5s ease, color 0.5s ease;
        }
        /* Smooth transitions for theme-affected elements */
        nav, div, header, main, table, td, th, input, select, textarea, button {
            transition: background-color 0.5s ease, color 0.5s ease, border-color 0.5s ease, box-shadow 0.5s ease;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
 @include('layouts.navigation')

 <!-- Page Heading -->
 @isset($header)
 <header class="bg-white dark:bg-gray-800 shadow">
 <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
 {{ $header }}
 </div>
 </header>
 @endisset

 <!-- Page Content -->
 <main>
 {{ $slot }}
 </main>
 </div>
 </body>
</html>
