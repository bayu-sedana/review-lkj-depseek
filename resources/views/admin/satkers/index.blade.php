<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Master Data Satker
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                <a href="{{ route('admin.satkers.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Tambah Satker
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Kode</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Satker</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Jumlah User</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($satkers as $satker)
                            <tr>
                                <td class="px-4 py-3">{{ $satker->kode_satker }}</td>
                                <td class="px-4 py-3">{{ $satker->nama_satker }}</td>
                                <td class="px-4 py-3">{{ $satker->users_count }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('admin.satkers.edit', $satker) }}"
                                       class="text-indigo-600 hover:text-indigo-800">Edit</a>

                                    <form method="POST" action="{{ route('admin.satkers.destroy', $satker) }}"
                                          class="inline" onsubmit="return confirm('Hapus satker ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada data satker.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $satkers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
