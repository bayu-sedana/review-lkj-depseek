<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Satker
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.satkers.update', $satker) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="kode_satker" class="block text-sm font-medium text-gray-700">Kode Satker</label>
                        <input id="kode_satker" name="kode_satker" type="text"
                               value="{{ old('kode_satker', $satker->kode_satker) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('kode_satker')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_satker" class="block text-sm font-medium text-gray-700">Nama Satker</label>
                        <input id="nama_satker" name="nama_satker" type="text"
                               value="{{ old('nama_satker', $satker->nama_satker) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nama_satker')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Perbarui
                        </button>
                        <a href="{{ route('admin.satkers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
