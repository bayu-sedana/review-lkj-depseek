<x-layouts.auth>
    <x-slot name="title">Atur Ulang Kata Sandi — Review LKj</x-slot>

    <div class="mb-8">
        <h2 class="auth-heading text-2xl text-slate-900">Atur Ulang Kata Sandi</h2>
        <p class="mt-2 text-sm text-slate-500 font-medium">
            Buat kata sandi baru untuk akun Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="auth-label block text-sm text-slate-700 mb-2">
                Alamat Email
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
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
            <label for="password" class="auth-label block text-sm text-slate-700 mb-2">
                Kata Sandi Baru
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
            Simpan Kata Sandi Baru
        </button>
    </form>
</x-layouts.auth>
