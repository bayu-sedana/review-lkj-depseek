<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-2">
                        Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
                    </p>
                    <p class="text-sm text-gray-600">
                        Anda login sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>.
                    </p>
                </div>
            </div>

            @if (auth()->user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-3">
                        Kelola periode, penugasan, dan pantau progres review secara global.
                    </p>
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Buka Dashboard Admin
                    </a>
                </div>
            @elseif (auth()->user()->role === 'monev')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-3">
                        Lihat daftar Satker yang ditugaskan kepada Anda dan mulai review.
                    </p>
                    <a href="{{ route('monev.dashboard') }}"
                       class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Buka Dashboard Monev
                    </a>
                </div>
            @elseif (auth()->user()->role === 'satker')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-3">
                        Kelola target kinerja, unggah dokumen LKj, dan tanggapi catatan perbaikan.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('satker.sasaran.index') }}"
                           class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Target Kinerja
                        </a>
                        <a href="{{ route('satker.lkj.index') }}"
                           class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900">
                            Upload LKj
                        </a>
                        <a href="{{ route('satker.revisi.index') }}"
                           class="inline-flex items-center rounded-md bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700">
                            Perbaikan LKj
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
