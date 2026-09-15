<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Periode Review
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.periodes.update', $periode) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="tahun_lkj" class="block text-sm font-medium text-gray-700">Tahun LKj</label>
                        <input id="tahun_lkj" name="tahun_lkj" type="number" min="2000" max="2100"
                               value="{{ old('tahun_lkj', $periode->tahun_lkj) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('tahun_lkj')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tahun_review" class="block text-sm font-medium text-gray-700">Tahun Review</label>
                        <input id="tahun_review" name="tahun_review" type="number" min="2000" max="2100"
                               value="{{ old('tahun_review', $periode->tahun_review) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('tahun_review')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deadline_revisi" class="block text-sm font-medium text-gray-700">Deadline Revisi</label>
                        <input id="deadline_revisi" name="deadline_revisi" type="date"
                               value="{{ old('deadline_revisi', $periode->deadline_revisi?->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('deadline_revisi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="aktif" @selected(old('status', $periode->status) === 'aktif')>Aktif</option>
                            <option value="ditutup" @selected(old('status', $periode->status) === 'ditutup')>Ditutup</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Perbarui
                        </button>
                        <a href="{{ route('admin.periodes.index') }}"
                           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
