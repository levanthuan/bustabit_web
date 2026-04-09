@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 sm:py-12">
        <div
            class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white shadow-sm ring-1 ring-zinc-950/5"
        >
            <div class="border-b border-zinc-100 bg-gradient-to-r from-amber-50/80 via-white to-rose-50/50 px-6 py-8 sm:px-10 sm:py-10">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">
                    Xin chào, {{ auth()->user()->name }}
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-zinc-600 sm:text-base">
                    Dùng <span class="font-medium text-zinc-800">menu bên trái</span> (trên điện thoại: chạm
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded border border-zinc-200 bg-white align-middle text-zinc-500">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </span>
                    góc trên) để mở <span class="font-medium">TT3 · TT5 · TT7 · TT10</span> và xem
                    <span class="font-medium">count</span>, <span class="font-medium">busted</span>,
                    <span class="font-medium">dead_flg</span>.
                </p>
            </div>
            <div class="grid gap-3 p-4 sm:grid-cols-2 sm:gap-4 sm:p-6 lg:grid-cols-4">
                @foreach (['tt3' => 'TT3', 'tt5' => 'TT5', 'tt7' => 'TT7', 'tt10' => 'TT10'] as $key => $label)
                    <a
                        href="{{ route('case.show', $key) }}"
                        class="group flex flex-col rounded-2xl border border-zinc-200 bg-zinc-50/50 p-4 transition hover:border-amber-300/60 hover:bg-white hover:shadow-md"
                    >
                        <span class="text-lg font-bold text-zinc-900">{{ $label }}</span>
                        <span class="mt-1 text-xs text-zinc-500">Xem dữ liệu bảng</span>
                        <span
                            class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-amber-700 group-hover:text-amber-800"
                        >
                            Mở
                            <svg class="h-3.5 w-3.5 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
