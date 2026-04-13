@extends('layouts.guest')

@section('title', 'Đăng nhập')

@section('content')
    <div class="w-full max-w-[440px]">
        <div class="mb-8 text-center">
            <div
                class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-rose-600 text-xl font-bold text-white shadow-lg shadow-rose-500/25 ring-4 ring-white"
            >
                {{ str()->upper(str()->substr(config('app.name', 'BT'), 0, 2)) }}
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">Chào mừng trở lại</h1>
            <p class="mt-2 text-sm text-zinc-600">Đăng nhập vào {{ config('app.name', 'Bustabit Tracking') }}</p>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm">
            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-zinc-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com"
                        class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/30"
                    />
                    @error('email')
                        <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium text-zinc-700">Mật khẩu</label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 pr-11 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/30"
                        />
                        <button
                            type="button"
                            id="toggle-password"
                            aria-label="Hiện / ẩn mật khẩu"
                            class="absolute right-3 top-1/2 -translate-y-1/2 flex h-7 w-7 items-center justify-center rounded-lg text-zinc-400 transition hover:text-zinc-600"
                        >
                            {{-- Icon mắt (password ẩn) --}}
                            <svg id="icon-eye" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{-- Icon mắt gạch (password hiện) --}}
                            <svg id="icon-eye-off" class="hidden h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.228 6.228A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.885 3.163M6.228 6.228L3 3m3.228 3.228l3.65 3.65M17.772 17.772l3.228 3.228m-3.228-3.228l-3.65-3.65"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <script>
                    (function () {
                        const btn = document.getElementById('toggle-password');
                        const input = document.getElementById('password');
                        const iconEye = document.getElementById('icon-eye');
                        const iconEyeOff = document.getElementById('icon-eye-off');
                        if (!btn || !input) return;
                        btn.addEventListener('click', function () {
                            const isPassword = input.type === 'password';
                            input.type = isPassword ? 'text' : 'password';
                            iconEye.classList.toggle('hidden', isPassword);
                            iconEyeOff.classList.toggle('hidden', !isPassword);
                        });
                    })();
                </script>

                <label class="flex cursor-pointer items-center gap-3 text-sm text-zinc-600">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        @checked(old('remember'))
                        class="size-4 rounded border-zinc-300 text-amber-600 focus:ring-amber-500/40 focus:ring-offset-0"
                    />
                    Ghi nhớ đăng nhập trên thiết bị này
                </label>

                <button
                    type="submit"
                    class="mt-1 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-amber-500 to-rose-600 px-4 py-3.5 text-sm font-semibold text-white shadow-md shadow-rose-500/20 transition hover:from-amber-400 hover:to-rose-500 hover:shadow-rose-500/30 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:ring-offset-2 focus:ring-offset-white"
                >
                    Đăng nhập
                </button>
            </form>
        </div>

        <p class="mt-8 text-center text-sm text-zinc-600">
            <a
                href="{{ route('home') }}"
                class="font-medium text-zinc-700 underline decoration-zinc-300 underline-offset-4 transition hover:text-amber-700 hover:decoration-amber-400/70"
            >
                Về trang chủ
            </a>
            <span class="mx-2 text-zinc-300">·</span>
            <span class="text-zinc-500">Cần tài khoản? Liên hệ quản trị.</span>
        </p>
    </div>
@endsection
