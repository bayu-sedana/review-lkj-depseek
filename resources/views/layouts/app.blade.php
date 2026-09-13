<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Review LKj') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col">
                @include('layouts.navigation')

                <main class="flex-1 p-6">
                    @if (session('success'))
                        <div class="mb-4 rounded-md bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-md bg-red-100 border border-red-300 text-red-800 px-4 py-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
