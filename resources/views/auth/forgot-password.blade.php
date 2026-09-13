<x-layouts.auth>
    <x-slot name="title">Lupa Kata Sandi — Review LKj</x-slot>

    <div class="mb-8">
        <h2 class="auth-heading text-2xl text-slate-900">Lupa Kata Sandi?</h2>
        <p class="mt-2 text-sm text-slate-500 font-medium">
            Masukkan alamat email Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-cyan-50 border border-cyan-200 px-4 py-3 text-sm text-cyan-800 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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
                placeholder="nama@instansi.go.id"
                class="auth-input block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 transition"
            />
            @error('email')
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="auth-button w-full inline-flex items-center justify-center rounded-lg px-4 py-3 text-sm text-white"
        >
            Kirim Tautan Reset
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-500 font-medium">
        Ingat kata sandi Anda?
        <a href="{{ route('login') }}" class="font-semibold text-blue-700 hover:text-blue-800 transition">
            Kembali ke halaman masuk
        </a>
    </p>
</x-layouts.auth>
