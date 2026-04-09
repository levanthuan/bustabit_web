@extends('layouts.app')

@section('title', $gameLabel)

@section('header_subtitle', $gameLabel)

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-10">
        <div class="mb-6 flex flex-col gap-1 sm:mb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">{{ $gameLabel }}</h1>
                <p class="mt-1 text-sm text-zinc-600">
                    Bảng
                    <code class="rounded-md bg-zinc-200/90 px-2 py-0.5 font-mono text-xs text-zinc-800">{{ $tableName }}</code>
                </p>
            </div>
        </div>

        @if ($record === null)
            <div
                class="rounded-3xl border border-dashed border-zinc-300 bg-white p-10 text-center shadow-sm sm:p-14"
            >
                <p class="text-sm font-medium text-zinc-700">Chưa có bản ghi nào trong bảng.</p>
                <p class="mx-auto mt-2 max-w-md text-xs leading-relaxed text-zinc-500">
                    Bản ghi mới nhất (theo <span class="font-medium text-zinc-700">game_datetime</span>, sau đó
                    <span class="font-medium text-zinc-700">id</span>) sẽ hiển thị tại đây.
                </p>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    class="rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm ring-1 ring-zinc-950/5 transition hover:shadow-md"
                >
                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">count</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-zinc-900 sm:text-4xl">
                        {{ $record->count ?? '—' }}
                    </p>
                </div>
                <div
                    class="rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm ring-1 ring-zinc-950/5 transition hover:shadow-md"
                >
                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">busted</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-zinc-900 sm:text-4xl">{{ $record->busted }}</p>
                </div>
                <div
                    class="rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm ring-1 ring-zinc-950/5 transition hover:shadow-md sm:col-span-2 lg:col-span-1"
                >
                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">dead_flg</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-zinc-900 sm:text-4xl">
                        @if ($record->dead_flg === null)
                            —
                        @else
                            {{ (int) $record->dead_flg }}
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-zinc-500">
                        @if ($record->dead_flg === 1)
                            Cờ bật
                        @elseif ($record->dead_flg === 0)
                            Cờ tắt
                        @endif
                    </p>
                </div>
            </div>

            <dl
                class="mt-6 grid gap-4 rounded-3xl border border-zinc-200/80 bg-white p-6 shadow-sm ring-1 ring-zinc-950/5 sm:grid-cols-2 sm:p-8"
            >
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-500">game_datetime</dt>
                    <dd class="mt-1 font-mono text-sm font-medium text-zinc-800">
                        {{ $record->game_datetime?->format('Y-m-d H:i:s') ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-500">id bản ghi</dt>
                    <dd class="mt-1 font-mono text-sm font-medium text-zinc-800">{{ $record->id }}</dd>
                </div>
            </dl>
        @endif
    </div>
@endsection
