<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload Dokumen LKj
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

                    @if ($submission)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-500">Status Keseluruhan</p>
                            <p class="font-semibold text-gray-800">
                                {{ ucwords(str_replace('_', ' ', $submission->status_keseluruhan)) }}
                            </p>
                        </div>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">
                        {{ $submission ? 'Unggah Versi Baru' : 'Unggah Dokumen LKj (V1)' }}
                    </h3>

                    <form method="POST" action="{{ route('satker.lkj.store') }}"
                          enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">
                                File LKj (PDF/DOC/DOCX, maks. 20 MB)
                            </label>
                            <input id="file" name="file" type="file" accept=".pdf,.doc,.docx"
                                   class="mt-1 block w-full text-sm text-gray-700">
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Unggah
                        </button>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-semibold text-gray-800">Riwayat Versi</h3>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Versi</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nama File</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Diunggah Oleh</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($submission?->dokumens ?? collect() as $dokumen)
                                <tr>
                                    <td class="px-4 py-3">V{{ $dokumen->versi }}</td>
                                    <td class="px-4 py-3">{{ $dokumen->file_name }}</td>
                                    <td class="px-4 py-3">{{ $dokumen->uploader->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $dokumen->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('satker.lkj.download', $dokumen) }}"
                                           class="text-indigo-600 hover:text-indigo-800">Unduh</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada dokumen yang diunggah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
