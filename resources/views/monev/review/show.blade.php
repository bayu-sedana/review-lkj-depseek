<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Review — {{ $penugasan->satker->kode_satker ?? '-' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Satker</p>
                        <p class="font-semibold text-gray-800">
                            {{ $penugasan->satker->kode_satker ?? '-' }} — {{ $penugasan->satker->nama_satker ?? '-' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Periode</p>
                        <p class="font-semibold text-gray-800">
                            LKj {{ $penugasan->periode->tahun_lkj ?? '-' }} / Review {{ $penugasan->periode->tahun_review ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Progres Review</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $persentase }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-indigo-600 h-3 rounded-full transition-all"
                             style="width: {{ $persentase }}%"></div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Total Poin: {{ $totalPoin }} (8 + 8 × jumlah indikator)
                    </p>
                </div>
            </div>

            @if (! $submission || ! $dokumen)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    Satker ini belum mengunggah dokumen LKj.
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ tab: 'aspek1' }">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button type="button" @click="tab = 'aspek1'"
                                    :class="tab === 'aspek1' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-3 text-sm font-medium border-b-2">
                                Aspek 1: Format
                            </button>
                            <button type="button" @click="tab = 'aspek2'"
                                    :class="tab === 'aspek2' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-3 text-sm font-medium border-b-2">
                                Aspek 2: Capaian
                            </button>
                            <button type="button" @click="tab = 'aspek3'"
                                    :class="tab === 'aspek3' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="px-6 py-3 text-sm font-medium border-b-2">
                                Aspek 3: Pengungkapan
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        {{-- Aspek 1: Format Pelaporan (per dokumen) --}}
                        <div x-show="tab === 'aspek1'" x-cloak>
                            <h3 class="text-base font-semibold text-gray-800 mb-4">Aspek 1: Kesesuaian Format Pelaporan</h3>

                            <form method="POST" action="{{ route('monev.review.aspek1', $penugasan) }}" class="space-y-4">
                                @csrf

                                @forelse ($rubrikFormat as $index => $rubrik)
                                    @php $hasil = $hasilReviews->get($rubrik->id.'-null'); @endphp
                                    <div class="border border-gray-200 rounded-md p-4"
                                         x-data="{ status: '{{ $hasil->status ?? '' }}' }">
                                        <input type="hidden" name="reviews[{{ $index }}][rubrik_id]" value="{{ $rubrik->id }}">

                                        <p class="font-medium text-gray-800">{{ $rubrik->bagian_laporan }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $rubrik->minimum_informasi }}</p>

                                        <div class="mt-3">
                                            <label class="block text-sm font-medium text-gray-700">Uraian Hasil Review</label>
                                            <textarea name="reviews[{{ $index }}][uraian_hasil_review]" rows="2"
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $hasil->uraian_hasil_review ?? '' }}</textarea>
                                        </div>

                                        <div class="mt-3 flex items-center gap-6">
                                            <label class="inline-flex items-center gap-2 text-sm">
                                                <input type="radio" name="reviews[{{ $index }}][status]" value="Sesuai"
                                                       x-model="status" @checked(($hasil->status ?? '') === 'Sesuai')>
                                                Sesuai
                                            </label>
                                            <label class="inline-flex items-center gap-2 text-sm">
                                                <input type="radio" name="reviews[{{ $index }}][status]" value="Belum Sesuai"
                                                       x-model="status" @checked(($hasil->status ?? '') === 'Belum Sesuai')>
                                                Belum Sesuai
                                            </label>
                                        </div>

                                        <div class="mt-3" x-show="status === 'Belum Sesuai'" x-cloak>
                                            <label class="block text-sm font-medium text-gray-700">Catatan untuk Perbaikan</label>
                                            <textarea name="reviews[{{ $index }}][catatan_perbaikan]" rows="2"
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $hasil->catatan_perbaikan ?? '' }}</textarea>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-400 italic">Rubrik Aspek 1 belum di-seed.</p>
                                @endforelse

                                @if ($rubrikFormat->isNotEmpty())
                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                            Simpan Aspek 1
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>

                        {{-- Aspek 2: Capaian Kinerja (per indikator, auto-compare) --}}
                        <div x-show="tab === 'aspek2'" x-cloak>
                            <h3 class="text-base font-semibold text-gray-800 mb-4">Aspek 2: Kesesuaian Data Capaian Kinerja</h3>

                            @forelse ($sasarans as $sasaran)
                                <div class="mb-6">
                                    <p class="font-medium text-gray-800 mb-2">{{ $sasaran->sasaran_kegiatan }}</p>

                                    @forelse ($sasaran->indikatorKinerja as $indikator)
                                        <div class="border border-gray-200 rounded-md p-4 mb-2">
                                            <p class="text-sm text-gray-700">{{ $indikator->indikator_kinerja }}</p>
                                            <p class="text-xs text-gray-400 mt-2 italic">
                                                Form auto-compare 5 nilai akan tersedia pada Fase 9.
                                            </p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-400 italic">Belum ada indikator.</p>
                                    @endforelse
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 italic">Satker belum mengisi sasaran kegiatan.</p>
                            @endforelse
                        </div>

                        {{-- Aspek 3: Pengungkapan Informasi (per indikator) --}}
                        <div x-show="tab === 'aspek3'" x-cloak>
                            <h3 class="text-base font-semibold text-gray-800 mb-4">Aspek 3: Pengungkapan Informasi Kinerja</h3>

                            <form method="POST" action="{{ route('monev.review.aspek3', $penugasan) }}" class="space-y-6">
                                @csrf

                                @php $rowIndex = 0; @endphp

                                @forelse ($sasarans as $sasaran)
                                    <div>
                                        <p class="font-medium text-gray-800 mb-2">{{ $sasaran->sasaran_kegiatan }}</p>

                                        @forelse ($sasaran->indikatorKinerja as $indikator)
                                            <div class="border border-gray-200 rounded-md p-4 mb-3">
                                                <p class="text-sm font-medium text-gray-700 mb-3">{{ $indikator->indikator_kinerja }}</p>

                                                @foreach ($rubrikPengungkapan as $rubrik)
                                                    @php
                                                        $hasil = $hasilReviews->get($rubrik->id.'-'.$indikator->id);
                                                        $key = $rowIndex++;
                                                    @endphp
                                                    <div class="border-t border-gray-100 pt-3 mt-3 first:border-t-0 first:pt-0 first:mt-0"
                                                         x-data="{ status: '{{ $hasil->status ?? '' }}' }">
                                                        <input type="hidden" name="reviews[{{ $key }}][rubrik_id]" value="{{ $rubrik->id }}">
                                                        <input type="hidden" name="reviews[{{ $key }}][indikator_kinerja_id]" value="{{ $indikator->id }}">

                                                        <p class="text-sm text-gray-600">{{ $rubrik->bagian_laporan }}</p>
                                                        <p class="text-xs text-gray-400 mt-1">{{ $rubrik->minimum_informasi }}</p>

                                                        <div class="mt-2">
                                                            <label class="block text-sm font-medium text-gray-700">Uraian Hasil Review</label>
                                                            <textarea name="reviews[{{ $key }}][uraian_hasil_review]" rows="2"
                                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $hasil->uraian_hasil_review ?? '' }}</textarea>
                                                        </div>

                                                        <div class="mt-2 flex items-center gap-6">
                                                            <label class="inline-flex items-center gap-2 text-sm">
                                                                <input type="radio" name="reviews[{{ $key }}][status]" value="Sesuai"
                                                                       x-model="status" @checked(($hasil->status ?? '') === 'Sesuai')>
                                                                Sesuai
                                                            </label>
                                                            <label class="inline-flex items-center gap-2 text-sm">
                                                                <input type="radio" name="reviews[{{ $key }}][status]" value="Belum Sesuai"
                                                                       x-model="status" @checked(($hasil->status ?? '') === 'Belum Sesuai')>
                                                                Belum Sesuai
                                                            </label>
                                                        </div>

                                                        <div class="mt-2" x-show="status === 'Belum Sesuai'" x-cloak>
                                                            <label class="block text-sm font-medium text-gray-700">Catatan untuk Perbaikan</label>
                                                            <textarea name="reviews[{{ $key }}][catatan_perbaikan]" rows="2"
                                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $hasil->catatan_perbaikan ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-400 italic">Belum ada indikator.</p>
                                        @endforelse
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-400 italic">Satker belum mengisi sasaran kegiatan.</p>
                                @endforelse

                                @if ($rubrikPengungkapan->isNotEmpty() && $sasarans->isNotEmpty())
                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                            Simpan Aspek 3
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
