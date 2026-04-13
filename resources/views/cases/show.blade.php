@extends('layouts.app')

@section('title', $gameLabel)

@section('header_subtitle', $date->translatedFormat('d/m/Y'))

@php
    $isToday = $date->isToday();
    $maxId = (int) ($records->max('id') ?? 0);
@endphp

@section('content')
    <div
        class="mx-auto w-full max-w-5xl px-4 py-4 sm:px-6"
        @if ($isToday)
            id="case-live-root"
            data-case-poll="1"
            data-case-date="{{ $date->toDateString() }}"
            data-case-after-id="{{ $maxId }}"
            data-case-poll-url="{{ route('case.records.since', $gameKey) }}"
        @endif
    >
        {{-- Điều hướng ngày: cuộn lên tới header thì dừng --}}
        <div class="sticky top-0 z-20 mb-3 flex items-center justify-between gap-2 border border-amber-200 bg-amber-50 px-4 py-3">
            <a
                @if ($prevDate)
                    href="{{ route('case.show', $gameKey) }}?date={{ $prevDate }}"
                @endif
                class="{{ $prevDate ? 'border-zinc-200 bg-white text-zinc-700 hover:border-zinc-300 hover:bg-zinc-50' : 'cursor-not-allowed border-zinc-100 bg-zinc-50 text-zinc-300' }} inline-flex items-center gap-1.5 border px-3 py-2 text-sm font-medium transition"
                @unless ($prevDate) aria-disabled="true" @endunless
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                @if ($prevDate)
                    {{ \Carbon\Carbon::parse($prevDate)->translatedFormat('d/m/Y') }}
                @else
                    Không có
                @endif
            </a>

            <div class="flex min-w-0 flex-1 flex-col items-center gap-0.5">
                <span class="text-base font-bold tabular-nums text-zinc-900">
                    {{ $gameLabel }} · {{ $date->translatedFormat('d/m/Y') }}
                </span>
                <span class="flex items-center gap-2 text-xs font-medium text-zinc-500">
                    <span id="case-record-count">{{ $records->count() }} bản ghi</span>
                    @if ($isToday)
                        <span
                            id="case-live-spinner"
                            class="inline-flex items-center gap-1 text-emerald-700"
                            aria-label="Đang cập nhật realtime"
                            title="Đang cập nhật realtime"
                        >
                            <svg class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span id="case-live-spinner-text" class="hidden sm:inline">Đang tải</span>
                        </span>
                    @endif
                </span>
            </div>

            <a
                @if ($nextDate)
                    href="{{ route('case.show', $gameKey) }}?date={{ $nextDate }}"
                @endif
                class="{{ $nextDate ? 'border-zinc-200 bg-white text-zinc-700 hover:border-zinc-300 hover:bg-zinc-50' : 'cursor-not-allowed border-zinc-100 bg-zinc-50 text-zinc-300' }} inline-flex items-center gap-1.5 border px-3 py-2 text-sm font-medium transition"
                @unless ($nextDate) aria-disabled="true" @endunless
            >
                @if ($nextDate)
                    {{ \Carbon\Carbon::parse($nextDate)->translatedFormat('d/m/Y') }}
                @else
                    Không có
                @endif
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        {{-- Bảng dữ liệu (luôn có tbody để poll append khi đang xem hôm nay) --}}
        <div id="case-empty-state" class="{{ $records->isEmpty() ? '' : 'hidden' }} rounded-2xl border border-dashed border-zinc-300 bg-white p-10 text-center shadow-sm sm:p-12">
            <p class="text-sm font-medium text-zinc-700">Không có dữ liệu cho ngày {{ $gameLabel }} · {{ $date->translatedFormat('d/m/Y') }}.</p>
            <p class="mt-2 text-xs text-zinc-500">Dùng mũi tên phía trên để chọn ngày khác.</p>
        </div>

        <div id="case-table-panel" class="{{ $records->isEmpty() ? 'hidden' : '' }} overflow-x-auto border border-zinc-200 bg-white">
            <table class="w-full min-w-[620px] text-sm">
                <thead class="sticky top-0 z-10 border-b border-amber-300 bg-amber-100 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-amber-900">
                            ID
                        </th>
                        <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-amber-900">
                            Đếm
                        </th>
                        <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-amber-900">
                            Giá trị
                        </th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-amber-900">
                            Cầu chết
                        </th>
                        <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-amber-900">
                            Thời gian
                        </th>
                    </tr>
                </thead>
                <tbody id="case-records-tbody" class="divide-y divide-zinc-200">
                    @foreach ($records as $record)
                        @php $isDead = (int) $record->dead_flg === 1; @endphp
                        <tr data-record-id="{{ $record->id }}" data-record-datetime="{{ $record->game_datetime?->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s') }}" class="{{ $isDead ? 'border-l-4 border-rose-500 bg-rose-100 hover:bg-rose-100/80' : 'hover:bg-zinc-50' }} transition">
                            <td class="px-4 py-2 text-left font-mono text-xs tabular-nums {{ $isDead ? 'text-rose-400' : 'text-zinc-400' }}">
                                {{ $record->id }}
                            </td>
                            <td class="px-4 py-2 text-right font-bold tabular-nums {{ $isDead ? 'text-rose-900' : 'text-zinc-900' }}">
                                {{ $record->count ?? '—' }}
                            </td>
                            <td class="px-4 py-2 text-right font-semibold tabular-nums {{ $isDead ? 'text-rose-800' : 'text-zinc-700' }}">
                                {{ $record->busted }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if ($record->dead_flg === null)
                                    <span class="text-zinc-300">—</span>
                                @elseif ($isDead)
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-rose-600 px-2.5 py-0.5 text-xs font-bold text-white shadow-sm shadow-rose-300"
                                        title="dead_flg = 1"
                                    >
                                        ★ Dead
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">
                                        0
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right font-mono text-xs tabular-nums {{ $isDead ? 'text-rose-400' : 'text-zinc-400' }}">
                                {{ $record->game_datetime?->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                @if ($isToday)
                    <tfoot id="case-loading-row" class="border-t border-zinc-200 bg-zinc-50">
                        <tr>
                            <td colspan="5" class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2 text-xs font-medium text-zinc-500">
                                    <svg class="h-3.5 w-3.5 animate-spin text-emerald-700" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    <span id="case-loading-row-text">Đang tải dữ liệu mới...</span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
