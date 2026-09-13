<x-layouts.auth>
    <x-slot name="title">Daftar — Review LKj</x-slot>

    <div class="mb-8">
        <h2 class="auth-heading text-2xl text-slate-900">Buat Akun Baru</h2>
        <p class="mt-2 text-sm text-slate-500 font-medium">
            Lengkapi data di bawah untuk mendaftar sebagai Operator Satker.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="auth-label block text-sm text-slate-700 mb-2">
                Nama Lengkap
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Nama lengkap Anda"
                class="auth-input block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 transition"
            />
            @error('name')
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

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
                autocomplete="username"
                placeholder="nama@instansi.go.id"
                class="auth-input block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 transition"
            />
            @error('email')
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="auth-label block text-sm text-slate-700 mb-2">
                Kata Sandi
            </label>
            <x-password-input
                id="password"
                name="password"
                autocomplete="new-password"
                :required="true"
            />
            @error('password')
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="auth-label block text-sm text-slate-700 mb-2">
                Konfirmasi Kata Sandi
            </label>
            <x-password-input
                id="password_confirmation"
                name="password_confirmation"
                autocomplete="new-password"
                :required="true"
            />
            @error('password_confirmation')
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="auth-button w-full inline-flex items-center justify-center rounded-lg px-4 py-3 text-sm text-white"
        >
            Daftar
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-500 font-medium">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-blue-700 hover:text-blue-800 transition">
            Masuk di sini
        </a>
    </p>
</x-layouts.auth>
