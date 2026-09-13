@php
    $role = auth()->user()->role ?? null;
@endphp

<aside class="w-64 bg-gray-900 text-gray-100 min-h-screen flex-shrink-0">
    <div class="px-6 py-5 border-b border-gray-800">
        <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-white">
            Review LKj
        </a>
        <p class="text-xs text-gray-400 mt-1">
            {{ ucfirst($role ?? 'guest') }}
        </p>
    </div>

    <nav class="px-3 py-4 space-y-1 text-sm">
        <a href="{{ route('dashboard') }}"
           class="block rounded-md px-3 py-2 hover:bg-gray-800 {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
            Dashboard
        </a>

        @if ($role === 'admin')
            <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-gray-500">Master Data</p>

            <a href="{{ route('admin.satkers.index') }}"
               class="block rounded-md px-3 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.satkers.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                Satker
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="block rounded-md px-3 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                User
            </a>

            <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-gray-500">Periode & Penugasan</p>

            <a href="{{ route('admin.periodes.index') }}"
               class="block rounded-md px-3 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.periodes.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                Periode Review
            </a>

            <a href="{{ route('admin.penugasan.index') }}"
               class="block rounded-md px-3 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.penugasan.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                Penugasan Monev
            </a>
        @endif

        @if ($role === 'monev')
            <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-gray-500">Monev</p>
            <span class="block px-3 py-2 text-gray-500 italic">Belum tersedia</span>
        @endif

        @if ($role === 'satker')
            <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-gray-500">Satker</p>
            <span class="block px-3 py-2 text-gray-500 italic">Belum tersedia</span>
        @endif
    </nav>
</aside>
