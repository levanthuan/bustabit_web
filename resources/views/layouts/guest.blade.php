<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Đăng nhập') — {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-full bg-zinc-950 font-sans text-zinc-100 antialiased">
        {{-- Nền gradient + lưới --}}
        <div class="fixed inset-0 overflow-hidden" aria-hidden="true">
            <div
                class="absolute -left-1/4 top-0 h-[600px] w-[600px] rounded-full bg-amber-500/20 blur-[120px] dark:bg-amber-500/15"
            ></div>
            <div
                class="absolute -right-1/4 bottom-0 h-[500px] w-[500px] rounded-full bg-rose-600/25 blur-[100px] dark:bg-rose-600/20"
            ></div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#27272a_1px,transparent_1px),linear-gradient(to_bottom,#27272a_1px,transparent_1px)] bg-[size:48px_48px] [mask-image:radial-gradient(ellipse_80%_60%_at_50%_40%,#000_50%,transparent_100%)] opacity-40 dark:opacity-50"
            ></div>
        </div>

        <div class="relative flex min-h-full flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
            @yield('content')
        </div>
    </body>
</html>
