<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Perbaikan LKj
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
            @elseif (! $dokumen)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    Anda belum mengunggah dokumen LKj pada periode ini.
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

                    <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Item Perbaikan</p>
                            <p class="font-semibold text-gray-800">{{ $jumlahPerbaikan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Belum Ditanggapi</p>
                            <p class="font-semibold {{ $jumlahBelumDitanggapi > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $jumlahBelumDitanggapi }}
                            </p>
                        </div>
                    </div>
                </div>

                @if ($jumlahPerbaikan === 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-green-600">
                        Tidak ada item yang perlu diperbaiki. Dokumen LKj Anda sudah sesuai.
                    </div>
                @else
                    <form method="POST" action="{{ route('satker.revisi.store') }}" class="space-y-6" x-data="{ tab: 'aspek1' }">
                        @csrf

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="border-b border-gray-200">
                                <nav class="flex -mb-px" aria-label="Tabs">
                                    <button type="button" @click="tab = 'aspek1'"
                                            :class="tab === 'aspek1' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                            class="flex-1 py-3 px-4 text-center text-sm font-medium border-b-2">
                                        Aspek 1: Format
                                        <span class="ml-1 text-xs text-gray-400">({{ $aspek1->count() }})</span>
                                    </button>
                                    <button type="button" @click="tab = 'aspek2'"
                                            :class="tab === 'aspek2' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                            class="flex-1 py-3 px-4 text-center text-sm font-medium border-b-2">
                                        Aspek 2: Capaian Kinerja
                                        <span class="ml-1 text-xs text-gray-400">({{ $capaianKinerjas->count() }})</span>
                                    </button>
                                    <button type="button" @click="tab = 'aspek3'"
                                            :class="tab === 'aspek3' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                            class="flex-1 py-3 px-4 text-center text-sm font-medium border-b-2">
                                        Aspek 3: Pengungkapan
                                        <span class="ml-1 text-xs text-gray-400">({{ $aspek3->count() }})</span>
                                    </button>
                                </nav>
                            </div>

                            <div class="p-6">
                                <div x-show="tab === 'aspek1'" x-cloak>
                                    @forelse ($aspek1 as $index => $hasil)
                                        <div class="border border-gray-200 rounded-md p-4 mb-3">
                                            <input type="hidden" name="hasil_reviews[{{ $index }}][id]" value="{{ $hasil->id }}">

                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $hasil->rubrik->bagian_laporan ?? '-' }}
                                            </p>

                                            @if ($hasil->indikatorKinerja)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Indikator: {{ $hasil->indikatorKinerja->indikator_kinerja }}
                                                </p>
                                            @endif

                                            @if ($hasil->uraian_hasil_review)
                                                <p class="text-sm text-gray-600 mt-2">
                                                    <span class="font-medium">Uraian Hasil Review:</span>
                                                    {{ $hasil->uraian_hasil_review }}
                                                </p>
                                            @endif

                                            <div class="mt-2 rounded-md bg-red-50 border border-red-200 p-3">
                                                <p class="text-sm text-red-800">
                                                    <span class="font-medium">Catatan Perbaikan:</span>
                                                    {{ $hasil->catatan_perbaikan ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="mt-3">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Tanggapan Perbaikan <span class="text-red-600">*</span>
                                                </label>
                                                <textarea name="hasil_reviews[{{ $index }}][tanggapan_perbaikan_satker]" rows="2"
                                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $hasil->tanggapan_perbaikan_satker }}</textarea>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-sm text-gray-500">
                                            Tidak ada item perbaikan pada aspek ini.
                                        </p>
                                    @endforelse
                                </div>

                                <div x-show="tab === 'aspek2'" x-cloak>
                                    @forelse ($capaianKinerjas as $index => $capaian)
                                        <div class="border border-gray-200 rounded-md p-4 mb-3">
                                            <input type="hidden" name="capaian_kinerjas[{{ $index }}][id]" value="{{ $capaian->id }}">

                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $capaian->indikatorKinerja->indikator_kinerja ?? '-' }}
                                            </p>

                                            <div class="mt-2 grid grid-cols-2 md:grid-cols-5 gap-2 text-xs text-gray-600">
                                                <div>Exec Summary: <strong>{{ $capaian->nilai_exec_summary ?? '-' }}</strong></div>
                                                <div>Bab III: <strong>{{ $capaian->nilai_bab_3 ?? '-' }}</strong></div>
                                                <div>Bab IV: <strong>{{ $capaian->nilai_bab_4 ?? '-' }}</strong></div>
                                                <div>Aplikasi: <strong>{{ $capaian->nilai_aplikasi_kinerjaku ?? '-' }}</strong></div>
                                                <div>Data Dukung: <strong>{{ $capaian->nilai_data_dukung ?? '-' }}</strong></div>
                                            </div>

                                            <div class="mt-2 rounded-md bg-red-50 border border-red-200 p-3">
                                                <p class="text-sm text-red-800">
                                                    <span class="font-medium">Catatan Perbaikan:</span>
                                                    {{ $capaian->catatan_perbaikan ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="mt-3">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Tanggapan Perbaikan <span class="text-red-600">*</span>
                                                </label>
                                                <textarea name="capaian_kinerjas[{{ $index }}][tanggapan_perbaikan_satker]" rows="2"
                                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $capaian->tanggapan_perbaikan_satker }}</textarea>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-sm text-gray-500">
                                            Tidak ada item perbaikan pada aspek ini.
                                        </p>
                                    @endforelse
                                </div>

                                <div x-show="tab === 'aspek3'" x-cloak>
                                    @forelse ($aspek3 as $index => $hasil)
                                        <div class="border border-gray-200 rounded-md p-4 mb-3">
                                            <input type="hidden" name="hasil_reviews[{{ $index }}][id]" value="{{ $hasil->id }}">

                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $hasil->rubrik->bagian_laporan ?? '-' }}
                                            </p>

                                            @if ($hasil->indikatorKinerja)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Indikator: {{ $hasil->indikatorKinerja->indikator_kinerja }}
                                                </p>
                                            @endif

                                            @if ($hasil->uraian_hasil_review)
                                                <p class="text-sm text-gray-600 mt-2">
                                                    <span class="font-medium">Uraian Hasil Review:</span>
                                                    {{ $hasil->uraian_hasil_review }}
                                                </p>
                                            @endif

                                            <div class="mt-2 rounded-md bg-red-50 border border-red-200 p-3">
                                                <p class="text-sm text-red-800">
                                                    <span class="font-medium">Catatan Perbaikan:</span>
                                                    {{ $hasil->catatan_perbaikan ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="mt-3">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Tanggapan Perbaikan <span class="text-red-600">*</span>
                                                </label>
                                                <textarea name="hasil_reviews[{{ $index }}][tanggapan_perbaikan_satker]" rows="2"
                                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $hasil->tanggapan_perbaikan_satker }}</textarea>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-sm text-gray-500">
                                            Tidak ada item perbaikan pada aspek ini.
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                Setelah seluruh tanggapan disimpan, unggah dokumen LKj versi berikutnya
                                melalui halaman
                                <a href="{{ route('satker.lkj.index') }}" class="text-indigo-600 hover:text-indigo-800 underline">
                                    Unggah LKj
                                </a>.
                            </p>
                            <button type="submit"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                Simpan Tanggapan
                            </button>
                        </div>
                    </form>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
