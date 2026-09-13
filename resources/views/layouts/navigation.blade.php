<header class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-3">
        <div class="text-sm text-gray-600">
            {{ auth()->user()->name ?? '' }}
            @if (auth()->user()?->satker)
                <span class="text-gray-400">— {{ auth()->user()->satker->nama_satker }}</span>
            @endif
        </div>

        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-900">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-gray-900">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
