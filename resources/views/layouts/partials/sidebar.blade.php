@php
    $items = [
        ['key' => 'tt3', 'label' => 'TT3', 'hint' => 'case_3', 'badge' => '3'],
        ['key' => 'tt5', 'label' => 'TT5', 'hint' => 'case_5', 'badge' => '5'],
        ['key' => 'tt7', 'label' => 'TT7', 'hint' => 'case_7', 'badge' => '7'],
        ['key' => 'tt10', 'label' => 'TT10', 'hint' => 'case_10', 'badge' => '10'],
    ];
@endphp

<aside
    id="admin-sidebar"
    class="flex flex-col border-zinc-200/80 bg-gradient-to-b from-white to-zinc-50 lg:border-r"
    aria-label="Menu điều hướng"
>
    <div
        class="flex h-14 shrink-0 items-center justify-between gap-2 border-b border-zinc-200/80 bg-white/80 px-3 backdrop-blur-sm sm:h-16 lg:px-2"
    >
        <p class="admin-sidebar-hide-collapsed min-w-0 truncate text-xs font-semibold uppercase tracking-wider text-zinc-500">
            Điều hướng
        </p>
        <div class="flex shrink-0 items-center gap-1">
            <button
                type="button"
                id="admin-sidebar-toggle"
                class="hidden h-9 w-9 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-600 transition hover:border-zinc-300 hover:bg-zinc-50 lg:inline-flex"
                aria-label="Thu gọn sidebar"
                aria-expanded="true"
                title="Thu gọn / mở rộng menu"
            >
                {{-- Mở rộng: mũi tên phải (khi đang thu gọn) --}}
                <svg
                    class="admin-sidebar-toggle-icon-expand h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                {{-- Thu gọn: mũi tên trái (khi đang mở rộng) --}}
                <svg
                    class="admin-sidebar-toggle-icon-collapse h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
            <button
                type="button"
                id="admin-drawer-close"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-600 transition hover:bg-zinc-50 lg:hidden"
                aria-label="Đóng menu"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-3 lg:px-2" aria-label="Mục chính">
        @foreach ($items as $item)
            @php
                $active = request()->routeIs('case.show') && request()->route('game') === $item['key'];
            @endphp
            <a
                href="{{ route('case.show', $item['key']) }}"
                title="{{ $item['label'] }} — {{ $item['hint'] }}"
                class="{{ $active
                    ? 'border-l-4 border-l-amber-500 bg-gradient-to-r from-amber-50 to-rose-50/80 text-zinc-900 shadow-sm ring-1 ring-amber-200/50'
                    : 'border-l-4 border-l-transparent text-zinc-600 hover:border-l-zinc-300 hover:bg-white hover:text-zinc-900 hover:shadow-sm' }} group flex items-center gap-3 rounded-xl px-3 py-3 transition lg:py-2.5"
            >
                <span
                    class="{{ $active ? 'bg-amber-500 text-white' : 'bg-zinc-200/80 text-zinc-600 group-hover:bg-zinc-300/80' }} flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold transition"
                >
                    {{ $item['badge'] }}
                </span>
                <span class="admin-sidebar-hide-collapsed min-w-0 flex-1">
                    <span class="block text-sm font-semibold">{{ $item['label'] }}</span>
                    <span class="block truncate text-xs text-zinc-500">{{ $item['hint'] }}</span>
                </span>
                <svg
                    class="admin-sidebar-nav-chevron {{ $active ? 'text-amber-600' : 'text-zinc-300 group-hover:text-zinc-500' }} h-4 w-4 shrink-0 transition"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @endforeach
    </nav>

    <div class="admin-sidebar-hide-collapsed mt-auto border-t border-zinc-200/80 bg-white/60 p-3 text-center">
        <p class="text-[10px] leading-tight text-zinc-400">Chọn mục để xem count · busted · dead_flg</p>
    </div>
</aside>
