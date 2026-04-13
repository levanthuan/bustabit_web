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

                        {{-- User dropdown --}}
                        <div class="relative ml-auto shrink-0" id="user-menu-wrapper">
                            <button
                                type="button"
                                id="user-menu-trigger"
                                aria-expanded="false"
                                aria-haspopup="menu"
                                aria-controls="user-menu-dropdown"
                                class="group flex items-center gap-2 rounded-xl border border-transparent px-2 py-1.5 transition hover:border-zinc-200 hover:bg-zinc-50 sm:gap-2.5 sm:px-3"
                            >
                                <div class="hidden text-right sm:block">
                                    <p class="max-w-[160px] truncate text-sm font-medium leading-tight text-zinc-800">{{ auth()->user()->name }}</p>
                                    <p class="max-w-[160px] truncate text-xs text-zinc-500">{{ auth()->user()->email }}</p>
                                </div>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-rose-500 text-xs font-bold text-white">
                                    {{ str()->upper(str()->substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <svg id="user-menu-chevron" class="h-3.5 w-3.5 shrink-0 text-zinc-400 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Dropdown menu --}}
                            <div
                                id="user-menu-dropdown"
                                role="menu"
                                class="absolute right-0 top-[calc(100%+6px)] z-50 hidden w-52 rounded-xl border border-zinc-200 bg-white py-1.5 shadow-lg shadow-zinc-200/70"
                            >
                                <button
                                    type="button"
                                    role="menuitem"
                                    data-open-modal="profile-modal"
                                    class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-zinc-700 transition hover:bg-zinc-50"
                                >
                                    <svg class="h-4 w-4 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Sửa hồ sơ
                                </button>
                                <button
                                    type="button"
                                    role="menuitem"
                                    data-open-modal="password-modal"
                                    class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-zinc-700 transition hover:bg-zinc-50"
                                >
                                    <svg class="h-4 w-4 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Đổi mật khẩu
                                </button>
                                <div class="my-1.5 border-t border-zinc-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        role="menuitem"
                                        class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-rose-600 transition hover:bg-rose-50"
                                    >
                                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Chỉ phần này scroll --}}
                <main class="flex-1 overflow-y-auto">
                    @yield('content')
                </main>
            </div>
        </div>

        {{-- Modal: Sửa hồ sơ --}}
        <dialog
            id="profile-modal"
            class="m-auto w-full max-w-md rounded-2xl border border-zinc-200 bg-white p-0 shadow-xl backdrop:bg-zinc-900/40 backdrop:backdrop-blur-sm"
        >
            <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                <h2 class="text-base font-semibold text-zinc-900">Sửa hồ sơ</h2>
                <button
                    type="button"
                    data-close-modal="profile-modal"
                    class="flex h-7 w-7 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600"
                    aria-label="Đóng"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5 p-6">
                @csrf
                @method('PATCH')

                <div class="space-y-1.5">
                    <label for="profile-name" class="text-sm font-medium text-zinc-700">Tên hiển thị</label>
                    <input
                        id="profile-name"
                        name="name"
                        type="text"
                        value="{{ old('name', auth()->user()->name) }}"
                        required
                        autocomplete="name"
                        class="w-full rounded-xl border px-4 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25 {{ $errors->profile->has('name') ? 'border-rose-400 bg-rose-50' : 'border-zinc-200 bg-white' }}"
                    />
                    @error('name', 'profile')
                        <p class="text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-1">
                    <button
                        type="button"
                        data-close-modal="profile-modal"
                        class="rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-300 hover:bg-zinc-50"
                    >
                        Hủy
                    </button>
                    <button
                        type="submit"
                        class="rounded-xl bg-gradient-to-r from-amber-500 to-rose-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:from-amber-400 hover:to-rose-500"
                    >
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </dialog>

        {{-- Modal: Đổi mật khẩu --}}
        <dialog
            id="password-modal"
            class="m-auto w-full max-w-md rounded-2xl border border-zinc-200 bg-white p-0 shadow-xl backdrop:bg-zinc-900/40 backdrop:backdrop-blur-sm"
        >
            <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                <h2 class="text-base font-semibold text-zinc-900">Đổi mật khẩu</h2>
                <button
                    type="button"
                    data-close-modal="password-modal"
                    class="flex h-7 w-7 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600"
                    aria-label="Đóng"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4 p-6">
                @csrf
                @method('PATCH')

                <div class="space-y-1.5">
                    <label for="current-password" class="text-sm font-medium text-zinc-700">Mật khẩu hiện tại</label>
                    <input
                        id="current-password"
                        name="current_password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border px-4 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25 {{ $errors->password->has('current_password') ? 'border-rose-400 bg-rose-50' : 'border-zinc-200 bg-white' }}"
                    />
                    @error('current_password', 'password')
                        <p class="text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="new-password" class="text-sm font-medium text-zinc-700">Mật khẩu mới</label>
                    <input
                        id="new-password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="Tối thiểu 8 ký tự"
                        class="w-full rounded-xl border px-4 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25 {{ $errors->password->has('password') ? 'border-rose-400 bg-rose-50' : 'border-zinc-200 bg-white' }}"
                    />
                    @error('password', 'password')
                        <p class="text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="password-confirmation" class="text-sm font-medium text-zinc-700">Xác nhận mật khẩu mới</label>
                    <input
                        id="password-confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25"
                    />
                </div>

                <div class="flex justify-end gap-3 pt-1">
                    <button
                        type="button"
                        data-close-modal="password-modal"
                        class="rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-300 hover:bg-zinc-50"
                    >
                        Hủy
                    </button>
                    <button
                        type="submit"
                        class="rounded-xl bg-gradient-to-r from-amber-500 to-rose-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:from-amber-400 hover:to-rose-500"
                    >
                        Đổi mật khẩu
                    </button>
                </div>
            </form>
        </dialog>

        {{-- Scroll navigation buttons (bottom-right) --}}
        <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2">
            <button
                id="back-to-top"
                type="button"
                aria-label="Lên đầu trang"
                title="Lên đầu trang"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-zinc-200 bg-white text-zinc-500 shadow-md transition-all duration-300 hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-800 hover:shadow-lg opacity-0 pointer-events-none"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                </svg>
            </button>
            <button
                id="scroll-to-bottom"
                type="button"
                aria-label="Xuống cuối trang"
                title="Xuống cuối trang"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-zinc-200 bg-white text-zinc-500 shadow-md transition-all duration-300 hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-800 hover:shadow-lg opacity-0 pointer-events-none"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        {{-- Toast thông báo thành công --}}
        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div
                id="success-toast"
                role="status"
                aria-live="polite"
                class="fixed left-1/2 top-5 z-[9999] flex -translate-x-1/2 items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-lg shadow-emerald-100 transition-opacity duration-500"
            >
                <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>
                    @if (session('status') === 'profile-updated')
                        Cập nhật thông tin thành công!
                    @else
                        Đổi mật khẩu thành công!
                    @endif
                </span>
                <button
                    type="button"
                    id="success-toast-close"
                    aria-label="Đóng thông báo"
                    class="-mr-1 ml-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg text-emerald-500 transition hover:bg-emerald-100 hover:text-emerald-700"
                >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const toast = document.getElementById('success-toast');
                    const closeBtn = document.getElementById('success-toast-close');
                    if (!toast) return;

                    function dismissToast() {
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 500);
                    }

                    closeBtn?.addEventListener('click', dismissToast);

                    const autoHide = setTimeout(dismissToast, 3000);
                    closeBtn?.addEventListener('click', () => clearTimeout(autoHide), { once: true });
                });
            </script>
        @endif

        {{-- Tự động mở modal khi có lỗi validation --}}
        @if ($errors->profile->any())
            <script>document.addEventListener('DOMContentLoaded', () => { document.getElementById('profile-modal')?.showModal(); });</script>
        @endif
        @if ($errors->password->any())
            <script>document.addEventListener('DOMContentLoaded', () => { document.getElementById('password-modal')?.showModal(); });</script>
        @endif
    </body>
</html>
