<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Target Kinerja
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (! $satker)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-red-600">
                    Akun Anda belum terhubung ke Satker manapun. Hubungi Admin.
                </div>
            @elseif (! $periode)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    Belum ada periode review aktif. Hubungi Admin.
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Satker</p>
                            <p class="font-semibold text-gray-800">
                                {{ $satker->kode_satker }} — {{ $satker->nama_satker }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Periode</p>
                            <p class="font-semibold text-gray-800">
                                LKj {{ $periode->tahun_lkj }} / Review {{ $periode->tahun_review }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Tambah Sasaran Kegiatan</h3>

                    <form method="POST" action="{{ route('satker.sasaran.store') }}" class="flex items-end gap-3">
                        @csrf
                        <div class="flex-1">
                            <label for="sasaran_kegiatan" class="block text-sm font-medium text-gray-700">Sasaran Kegiatan</label>
                            <textarea id="sasaran_kegiatan" name="sasaran_kegiatan" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('sasaran_kegiatan') }}</textarea>
                            @error('sasaran_kegiatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Tambah
                        </button>
                    </form>
                </div>

                @forelse ($sasarans as $sasaran)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                        <div class="flex items-end justify-between gap-4">
                            <form method="POST" action="{{ route('satker.sasaran.update', $sasaran) }}" class="flex-1 flex items-end gap-3">
                                @csrf
                                @method('PUT')
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700">Sasaran Kegiatan</label>
                                    <textarea name="sasaran_kegiatan" rows="2"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $sasaran->sasaran_kegiatan }}</textarea>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center rounded-md bg-gray-800 px-3 py-2 text-sm font-medium text-white hover:bg-gray-900">
                                    Simpan
                                </button>
                            </form>

                            <form method="POST" action="{{ route('satker.sasaran.destroy', $sasaran) }}"
                                  onsubmit="return confirm('Hapus sasaran ini beserta indikatornya?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-md border border-red-600 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                    Hapus
                                </button>
                            </form>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Indikator Kinerja</h4>

                            <ul class="space-y-2">
                                @forelse ($sasaran->indikatorKinerja as $indikator)
                                    <li class="flex items-end gap-3">
                                        <form method="POST" action="{{ route('satker.indikator.update', $indikator) }}" class="flex-1 flex items-end gap-3">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="indikator_kinerja"
                                                   value="{{ $indikator->indikator_kinerja }}"
                                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                                Simpan
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('satker.indikator.destroy', $indikator) }}"
                                              class="flex items-end"
                                              onsubmit="return confirm('Hapus indikator ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-red-600 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-400 italic">Belum ada indikator.</li>
                                @endforelse
                            </ul>

                            <form method="POST" action="{{ route('satker.indikator.store', $sasaran) }}" class="mt-3 flex items-end gap-3">
                                @csrf
                                <div class="flex-1">
                                    <input type="text" name="indikator_kinerja" placeholder="Tambah indikator kinerja..."
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @error('indikator_kinerja')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                    Tambah
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                        Belum ada sasaran kegiatan. Silakan tambahkan di atas.
                    </div>
                @endforelse
            @endif
        </div>
    </div>
</x-app-layout>
