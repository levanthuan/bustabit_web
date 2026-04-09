<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Trang chủ') — {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body
        class="flex min-h-full flex-col bg-zinc-50 font-sans text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100"
    >
        <header
            class="sticky top-0 z-50 border-b border-zinc-200/70 bg-white/75 backdrop-blur-xl dark:border-zinc-800/80 dark:bg-zinc-900/75"
        >
            <div class="mx-auto flex h-16 max-w-5xl items-center justify-between gap-4 px-4 sm:px-6">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-rose-600 text-sm font-bold text-white shadow-md shadow-rose-500/20 transition group-hover:shadow-lg group-hover:shadow-rose-500/30"
                    >
                        {{ str()->upper(str()->substr(config('app.name', 'B'), 0, 1)) }}
                    </span>
                    <span class="hidden flex-col sm:flex">
                        <span class="text-sm font-semibold leading-tight text-zinc-900 dark:text-white">
                            {{ config('app.name', 'Laravel') }}
                        </span>
                        <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Trang chủ</span>
                    </span>
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="hidden max-w-[200px] truncate text-right text-sm sm:block">
                        <p class="truncate font-medium text-zinc-800 dark:text-zinc-200">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:border-zinc-600 dark:hover:bg-zinc-700"
                        >
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>
    </body>
</html>
