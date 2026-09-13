<x-layouts.auth>
    <x-slot name="title">Masuk — Review LKj</x-slot>

    <div class="mb-8">
        <h2 class="auth-heading text-2xl text-slate-900">Selamat Datang</h2>
        <p class="mt-2 text-sm text-slate-500 font-medium">
            Silakan masuk untuk melanjutkan ke dashboard Anda.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-cyan-50 border border-cyan-200 px-4 py-3 text-sm text-cyan-800 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="auth-label block text-sm text-slate-700 mb-2">
                Alamat Email
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="nama@instansi.go.id"
                class="auth-input block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 transition"
            />
            @error('email')
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="auth-label block text-sm text-slate-700">
                    Kata Sandi
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs font-semibold text-blue-700 hover:text-blue-800 transition">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="auth-input block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 transition"
            />
            @error('password')
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-cyan-400"
            />
            <label for="remember_me" class="ml-2 text-sm text-slate-600 font-medium">
                Ingat saya
            </label>
        </div>

        <button
            type="submit"
            class="auth-button w-full inline-flex items-center justify-center rounded-lg px-4 py-3 text-sm text-white"
        >
            Masuk
        </button>
    </form>

    @if (Route::has('register'))
        <p class="mt-8 text-center text-sm text-slate-500 font-medium">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-blue-700 hover:text-blue-800 transition">
                Daftar sekarang
            </a>
        </p>
    @endif
</x-layouts.auth>
