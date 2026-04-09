@extends('layouts.guest')

@section('title', 'Đăng nhập')

@section('content')
    <div class="w-full max-w-[440px]">
        <div class="mb-8 text-center">
            <div
                class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-rose-600 text-xl font-bold text-white shadow-xl shadow-rose-600/30 ring-1 ring-white/20"
            >
                {{ str()->upper(str()->substr(config('app.name', 'B'), 0, 1)) }}
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">Chào mừng trở lại</h1>
            <p class="mt-2 text-sm text-zinc-400">Đăng nhập vào {{ config('app.name', 'ứng dụng') }}</p>
        </div>

        <div
            class="rounded-2xl border border-zinc-700/80 bg-zinc-900/60 p-8 shadow-2xl shadow-black/40 backdrop-blur-xl"
        >
            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-zinc-300">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com"
                        class="w-full rounded-xl border border-zinc-600/80 bg-zinc-950/50 px-4 py-3 text-sm text-white placeholder:text-zinc-600 transition focus:border-amber-500/60 focus:outline-none focus:ring-2 focus:ring-amber-500/30"
                    />
                    @error('email')
                        <p class="text-sm font-medium text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium text-zinc-300">Mật khẩu</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-zinc-600/80 bg-zinc-950/50 px-4 py-3 text-sm text-white placeholder:text-zinc-600 transition focus:border-amber-500/60 focus:outline-none focus:ring-2 focus:ring-amber-500/30"
                    />
                    @error('password')
                        <p class="text-sm font-medium text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex cursor-pointer items-center gap-3 text-sm text-zinc-400">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        @checked(old('remember'))
                        class="size-4 rounded-md border-zinc-600 bg-zinc-950 text-amber-500 focus:ring-amber-500/40 focus:ring-offset-0"
                    />
                    Ghi nhớ đăng nhập trên thiết bị này
                </label>

                <button
                    type="submit"
                    class="mt-1 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-amber-500 to-rose-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-rose-600/25 transition hover:from-amber-400 hover:to-rose-500 hover:shadow-rose-500/35 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:ring-offset-2 focus:ring-offset-zinc-900"
                >
                    Đăng nhập
                </button>
            </form>
        </div>

        <p class="mt-8 text-center text-sm text-zinc-500">
            <a
                href="{{ route('home') }}"
                class="font-medium text-zinc-400 underline decoration-zinc-600 underline-offset-4 transition hover:text-amber-400 hover:decoration-amber-400/60"
            >
                Về trang chủ
            </a>
            <span class="mx-2 text-zinc-700">·</span>
            <span class="text-zinc-600">Cần tài khoản? Liên hệ quản trị.</span>
        </p>
    </div>
@endsection
