@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 sm:py-14">
        <div class="mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">
                Xin chào, {{ auth()->user()->name }}
            </h1>
            <p class="mt-2 max-w-2xl text-base text-zinc-600 dark:text-zinc-400">
                Bạn đã đăng nhập thành công. Đây là trang chủ sau khi xác thực.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div
                class="relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/50 lg:col-span-2"
            >
                <div
                    class="pointer-events-none absolute -right-20 -top-20 h-40 w-40 rounded-full bg-gradient-to-br from-amber-400/20 to-rose-600/20 blur-2xl"
                ></div>
                <h2 class="relative text-lg font-semibold text-zinc-900 dark:text-white">Tài khoản</h2>
                <dl class="relative mt-6 space-y-4">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            Họ tên
                        </dt>
                        <dd class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}</dd>
                    </div>
                    <div class="h-px bg-zinc-100 dark:bg-zinc-800"></div>
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            Email
                        </dt>
                        <dd class="break-all text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ auth()->user()->email }}
                        </dd>
                    </div>
                    @if (auth()->user()->email_verified_at)
                        <div class="h-px bg-zinc-100 dark:bg-zinc-800"></div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400"
                            >
                                Email đã xác minh
                            </span>
                        </div>
                    @endif
                </dl>
            </div>

            <div
                class="flex flex-col justify-between rounded-2xl border border-zinc-200 bg-gradient-to-br from-zinc-900 to-zinc-950 p-6 text-white shadow-lg dark:border-zinc-800"
            >
                <div>
                    <h2 class="text-lg font-semibold">Trạng thái</h2>
                    <p class="mt-2 text-sm text-zinc-400">Phiên đăng nhập đang hoạt động.</p>
                </div>
                <div class="mt-8">
                    <div class="flex items-center gap-2 text-sm text-zinc-300">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"
                            ></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        </span>
                        Đã kết nối
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
