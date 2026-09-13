<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-end gap-3">
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

                @if ($selectedPeriode && $selectedPeriode->deadline_revisi)
                    <p class="mt-3 text-sm {{ $isLewatDeadline ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                        Deadline Revisi: {{ $selectedPeriode->deadline_revisi->format('d M Y') }}
                        @if ($isLewatDeadline)
                            — <span class="uppercase">Lewat Deadline</span>
                        @endif
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Total Satker</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $statistik['total_satker'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Belum Upload</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $statistik['belum_upload'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Sedang Review</p>
                    <p class="text-2xl font-semibold text-indigo-600">{{ $statistik['sedang_review'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Proses Revisi</p>
                    <p class="text-2xl font-semibold text-amber-600">{{ $statistik['proses_revisi'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Selesai</p>
                    <p class="text-2xl font-semibold text-green-600">{{ $statistik['selesai'] }}</p>
                </div>
            </div>

            @if ($isLewatDeadline && $submissionsLewatDeadline->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-semibold text-red-600">
                            Peringatan: Satker Belum Selesai Melewati Deadline
                        </h3>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Satker</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($submissionsLewatDeadline as $submission)
                                <tr>
                                    <td class="px-4 py-3">
                                        {{ $submission->satker->kode_satker ?? '-' }}
                                        — {{ $submission->satker->nama_satker ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-red-600 font-medium">
                                        {{ ucwords(str_replace('_', ' ', $submission->status_keseluruhan)) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
