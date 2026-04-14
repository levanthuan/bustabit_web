@extends('layouts.app')

@section('title', $gameLabel)

@section('header_subtitle', $date->translatedFormat('d/m/Y'))

@php
    $isToday = $date->isToday();
    $maxId = (int) ($records->max('id') ?? 0);
@endphp

@section('content')
    <div
        class="mx-auto w-full max-w-5xl px-0 py-0 sm:px-4 sm:py-4 md:px-6"
        @if ($isToday)
            id="case-live-root"
            data-case-poll="1"
            data-case-date="{{ $date->toDateString() }}"
            data-case-after-id="{{ $maxId }}"
            data-case-poll-url="{{ route('case.records.since', $gameKey) }}"
        @endif
    >
        {{-- Điều hướng ngày --}}
        <div id="case-date-nav" class="sticky top-0 z-20 mb-0 flex items-center justify-between gap-1 border border-amber-300 bg-amber-100 px-2 py-2 shadow-sm sm:mb-3 sm:gap-2 sm:px-4 sm:py-3">
            {{-- Nút trước --}}
            <a
                @if ($prevDate)
                    href="{{ route('case.show', $gameKey) }}?date={{ $prevDate }}"
                @endif
                class="{{ $prevDate ? 'border-zinc-200 bg-white text-zinc-700 hover:border-zinc-300 hover:bg-zinc-50' : 'cursor-not-allowed border-zinc-100 bg-zinc-50 text-zinc-300' }} inline-flex shrink-0 items-center gap-1 border px-2 py-1.5 text-xs font-medium transition sm:gap-1.5 sm:px-3 sm:py-2 sm:text-sm"
                @unless ($prevDate) aria-disabled="true" @endunless
            >
                <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                @if ($prevDate)
                    <span class="hidden sm:inline">{{ \Carbon\Carbon::parse($prevDate)->translatedFormat('d/m/Y') }}</span>
                @endif
            </a>

            {{-- Tiêu đề giữa --}}
            <div class="flex min-w-0 flex-1 flex-col items-center gap-0.5">
                <span class="truncate text-sm font-bold tabular-nums text-zinc-900 sm:text-base">
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

            {{-- Nút sau --}}
            <a
                @if ($nextDate)
                    href="{{ route('case.show', $gameKey) }}?date={{ $nextDate }}"
                @endif
                class="{{ $nextDate ? 'border-zinc-200 bg-white text-zinc-700 hover:border-zinc-300 hover:bg-zinc-50' : 'cursor-not-allowed border-zinc-100 bg-zinc-50 text-zinc-300' }} inline-flex shrink-0 items-center gap-1 border px-2 py-1.5 text-xs font-medium transition sm:gap-1.5 sm:px-3 sm:py-2 sm:text-sm"
                @unless ($nextDate) aria-disabled="true" @endunless
            >
                @if ($nextDate)
                    <span class="hidden sm:inline">{{ \Carbon\Carbon::parse($nextDate)->translatedFormat('d/m/Y') }}</span>
                @endif
                <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        {{-- Empty state --}}
        <div id="case-empty-state" class="{{ $records->isEmpty() ? '' : 'hidden' }} mx-2 my-3 rounded-2xl border border-dashed border-zinc-300 bg-white p-8 text-center shadow-sm sm:mx-0 sm:p-12">
            <p class="text-sm font-medium text-zinc-700">Không có dữ liệu cho ngày {{ $gameLabel }} · {{ $date->translatedFormat('d/m/Y') }}.</p>
            <p class="mt-2 text-xs text-zinc-500">Dùng mũi tên phía trên để chọn ngày khác.</p>
        </div>

        {{-- Bảng dữ liệu --}}
        <div id="case-table-panel" class="{{ $records->isEmpty() ? 'hidden' : '' }} border-x-0 border-y border-zinc-200 bg-white sm:border">
            <table class="w-full table-fixed text-sm">
                <colgroup>
                    {{-- ID: rộng hơn vì số dài --}}
                    <col class="w-[28%] sm:w-[30%]">
                    {{-- Đếm --}}
                    <col class="w-[13%]">
                    {{-- Giá trị --}}
                    <col class="w-[15%]">
                    {{-- Cầu chết --}}
                    <col class="w-[20%] sm:w-[18%]">
                    {{-- Thời gian --}}
                    <col class="w-[24%] sm:w-[24%]">
                </colgroup>
                {{-- Sticky on each th; top offset clears #case-date-nav (z-20) so headers stay visible. --}}
                <thead id="case-records-thead" class="font-sans">
                    <tr>
                        <th class="sticky top-[3.25rem] z-10 border-b border-amber-300 bg-amber-100 px-1.5 py-2.5 text-left text-xs font-semibold tracking-tight text-amber-900 shadow-sm sm:top-[4.25rem] sm:px-4 sm:text-sm">
                            ID
                        </th>
                        <th class="sticky top-[3.25rem] z-10 border-b border-amber-300 bg-amber-100 px-1.5 py-2.5 text-right text-xs font-semibold tracking-tight text-amber-900 shadow-sm sm:top-[4.25rem] sm:px-4 sm:text-sm">
                            Đếm
                        </th>
                        <th class="sticky top-[3.25rem] z-10 border-b border-amber-300 bg-amber-100 px-1.5 py-2.5 text-right text-xs font-semibold tracking-tight text-amber-900 shadow-sm sm:top-[4.25rem] sm:px-4 sm:text-sm">
                            Giá trị
                        </th>
                        <th class="sticky top-[3.25rem] z-10 border-b border-amber-300 bg-amber-100 px-1.5 py-2.5 text-center text-xs font-semibold tracking-tight text-amber-900 shadow-sm sm:top-[4.25rem] sm:px-4 sm:text-sm">
                            Cầu chết
                        </th>
                        <th class="sticky top-[3.25rem] z-10 border-b border-amber-300 bg-amber-100 px-1.5 py-2.5 text-right text-xs font-semibold tracking-tight text-amber-900 shadow-sm sm:top-[4.25rem] sm:px-4 sm:text-sm">
                            Giờ
                        </th>
                    </tr>
                </thead>
                <tbody id="case-records-tbody" class="divide-y divide-zinc-200">
                    @foreach ($records as $record)
                        @php $isDead = (int) $record->dead_flg === 1; @endphp
                        <tr
                            data-record-id="{{ $record->id }}"
                            data-record-datetime="{{ $record->game_datetime?->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s') }}"
                            class="{{ $isDead ? 'border-l-4 border-rose-500 bg-rose-100 hover:bg-rose-100/80' : 'hover:bg-zinc-50' }} transition"
                        >
                            <td class="truncate px-1.5 py-1.5 text-left font-mono text-[10px] tabular-nums {{ $isDead ? 'text-rose-400' : 'text-zinc-400' }} sm:px-4 sm:py-2 sm:text-xs">
                                {{ $record->id }}
                            </td>
                            <td class="px-1.5 py-1.5 text-right font-bold tabular-nums text-xs {{ $isDead ? 'text-rose-900' : 'text-zinc-900' }} sm:px-4 sm:py-2 sm:text-sm">
                                {{ $record->count ?? '—' }}
                            </td>
                            <td class="px-1.5 py-1.5 text-right font-semibold tabular-nums text-xs {{ $isDead ? 'text-rose-800' : 'text-zinc-700' }} sm:px-4 sm:py-2 sm:text-sm">
                                {{ $record->busted }}
                            </td>
                            <td class="px-1 py-1.5 text-center sm:px-4 sm:py-2">
                                @if ($record->dead_flg === null)
                                    <span class="text-zinc-300">—</span>
                                @elseif ($isDead)
                                    <span
                                        class="inline-flex items-center rounded-full bg-rose-600 px-1.5 py-0.5 text-[10px] font-bold text-white shadow-sm shadow-rose-300 sm:gap-1 sm:px-2.5 sm:text-xs"
                                        title="dead_flg = 1"
                                    >
                                        ★<span class="hidden sm:inline"> Dead</span>
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-100 px-1.5 py-0.5 text-[10px] font-medium text-zinc-500 sm:px-2 sm:text-xs">
                                        0
                                    </span>
                                @endif
                            </td>
                            <td class="px-1.5 py-1.5 text-right font-mono text-[10px] tabular-nums {{ $isDead ? 'text-rose-400' : 'text-zinc-400' }} sm:px-4 sm:py-2 sm:text-xs">
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
