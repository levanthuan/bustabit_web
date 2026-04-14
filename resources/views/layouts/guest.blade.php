<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="color-scheme" content="light">

        <title>@yield('title', 'Đăng nhập') — {{ config('app.name', 'Bustabit Tracking') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=roboto:400,500,600,700&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-full bg-zinc-50 font-sans font-medium text-zinc-900 antialiased">
        <div class="fixed inset-0 overflow-hidden" aria-hidden="true">
            <div
                class="absolute -left-1/4 top-0 h-[520px] w-[520px] rounded-full bg-amber-200/40 blur-[100px]"
            ></div>
            <div
                class="absolute -right-1/4 bottom-0 h-[420px] w-[420px] rounded-full bg-rose-200/50 blur-[90px]"
            ></div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#e4e4e7_1px,transparent_1px),linear-gradient(to_bottom,#e4e4e7_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_75%_55%_at_50%_45%,#000_45%,transparent_100%)] opacity-60"
            ></div>
        </div>

        <div class="relative flex min-h-full flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
            @yield('content')
        </div>
    </body>
</html>
