<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="color-scheme" content="light">

        <title>@yield('title', 'Trang chủ') — {{ config('app.name', 'Bustabit Tracking') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        {{-- Đọc localStorage đồng bộ để tránh FOUC khi sidebar có trạng thái thu gọn --}}
        <script>
            try {
                if (localStorage.getItem('admin-sidebar-collapsed') === '1' && window.matchMedia('(min-width: 64rem)').matches) {
                    document.documentElement.classList.add('admin-sidebar-collapsed');
                }
            } catch (e) { /* private mode */ }
        </script>
    </head>
    {{-- h-full + overflow-hidden: toàn trang không scroll, chỉ <main> scroll --}}
    <body class="h-full overflow-hidden bg-zinc-100/80 font-sans text-zinc-900 antialiased">
        <div class="flex h-full flex-col lg:flex-row">
            <div id="admin-overlay" class="lg:hidden" aria-hidden="true"></div>

            @include('layouts.partials.sidebar')

            {{-- Cột phải: chiếm phần còn lại, không tràn ra ngoài --}}
            <div class="flex h-full min-w-0 flex-1 flex-col overflow-hidden">
                <header
                    class="z-30 shrink-0 border-b border-zinc-200/80 bg-white/90 shadow-sm backdrop-blur-md supports-[backdrop-filter]:bg-white/75"
                >
                    <div class="flex h-14 items-center gap-3 px-3 sm:h-16 sm:gap-4 sm:px-5">
                        <button
                            type="button"
                            id="admin-drawer-open"
                            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-700 shadow-sm transition hover:border-zinc-300 hover:bg-zinc-50 lg:hidden"
                            aria-label="Mở menu"
                            aria-expanded="false"
                            aria-controls="admin-sidebar"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <a href="{{ route('home') }}" class="group flex min-w-0 flex-1 items-center gap-3 sm:flex-initial">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-rose-600 text-xs font-bold text-white shadow-md shadow-rose-500/20 sm:h-10 sm:w-10 sm:text-sm"
                            >
                                {{ str()->upper(str()->substr(config('app.name', 'BT'), 0, 1)) }}
                            </span>
                            <span class="hidden min-w-0 flex-col sm:flex">
                                <span class="truncate text-sm font-semibold leading-tight text-zinc-900">
                                    {{ config('app.name', 'Bustabit Tracking') }}
                                </span>
                                <span class="text-xs font-medium text-zinc-500">@yield('header_subtitle', 'Trang chủ')</span>
                            </span>
                        </a>

                        <div class="ml-auto flex shrink-0 items-center gap-2 sm:gap-3">
                            <div class="hidden max-w-[200px] truncate text-right text-sm md:block">
                                <p class="truncate font-medium text-zinc-800">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-zinc-500">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs font-medium text-zinc-700 shadow-sm transition hover:border-zinc-300 hover:bg-zinc-50 sm:px-4 sm:text-sm"
                                >
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                {{-- Chỉ phần này scroll --}}
                <main class="flex-1 overflow-y-auto">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
