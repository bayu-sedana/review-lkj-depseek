<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Periode Review
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                <a href="{{ route('admin.periodes.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Tambah Periode
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Tahun LKj</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Tahun Review</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Deadline Revisi</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Penugasan</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($periodes as $periode)
                            <tr>
                                <td class="px-4 py-3">{{ $periode->tahun_lkj }}</td>
                                <td class="px-4 py-3">{{ $periode->tahun_review }}</td>
                                <td class="px-4 py-3">
                                    {{ $periode->deadline_revisi?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($periode->status === 'aktif')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-200 px-2 py-1 text-xs font-medium text-gray-700">Ditutup</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $periode->penugasan_monev_count }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('admin.penugasan.index', ['periode_id' => $periode->id]) }}"
                                       class="text-gray-600 hover:text-gray-900">Penugasan</a>

                                    <a href="{{ route('admin.periodes.edit', $periode) }}"
                                       class="text-indigo-600 hover:text-indigo-800">Edit</a>

                                    <form method="POST" action="{{ route('admin.periodes.destroy', $periode) }}"
                                          class="inline" onsubmit="return confirm('Hapus periode ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada data periode review.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $periodes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
