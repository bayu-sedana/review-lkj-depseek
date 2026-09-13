<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Tim Monev
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('monev.dashboard') }}" class="flex items-end gap-3">
                    <div class="flex-1">
                        <label for="periode_id" class="block text-sm font-medium text-gray-700">Pilih Periode</label>
                        <select id="periode_id" name="periode_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @forelse ($periodes as $periode)
                                <option value="{{ $periode->id }}"
                                        @selected($selectedPeriode && $selectedPeriode->id === $periode->id)>
                                    {{ $periode->tahun_lkj }}/{{ $periode->tahun_review }}
                                    — {{ ucfirst($periode->status) }}
                                </option>
                            @empty
                                <option value="">Belum ada periode</option>
                            @endforelse
                        </select>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900">
                        Tampilkan
                    </button>
                </form>
            </div>

            @if ($selectedPeriode)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Satker</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Periode</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($penugasans as $penugasan)
                                <tr>
                                    <td class="px-4 py-3">
                                        {{ $penugasan->satker->kode_satker ?? '-' }}
                                        — {{ $penugasan->satker->nama_satker ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $penugasan->periode->tahun_lkj ?? '-' }}/{{ $penugasan->periode->tahun_review ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('monev.review.show', $penugasan) }}"
                                           class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                        Anda belum ditugaskan untuk Satker manapun pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    Belum ada periode review.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
