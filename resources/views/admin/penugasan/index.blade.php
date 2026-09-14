<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penugasan Tim Monev
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.penugasan.index') }}" class="flex items-end gap-3">
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Tambah Penugasan</h3>

                    <form method="POST" action="{{ route('admin.penugasan.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $selectedPeriode->id }}">

                        <div>
                            <label for="satker_id" class="block text-sm font-medium text-gray-700">Satker</label>
                            <select id="satker_id" name="satker_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($satkers as $satker)
                                    <option value="{{ $satker->id }}">
                                        {{ $satker->kode_satker }} — {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                            @error('satker_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="monev_user_id" class="block text-sm font-medium text-gray-700">Tim Monev</label>
                            <select id="monev_user_id" name="monev_user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @forelse ($monevUsers as $monev)
                                    <option value="{{ $monev->id }}">{{ $monev->name }}</option>
                                @empty
                                    <option value="">Belum ada user role Monev</option>
                                @endforelse
                            </select>
                            @error('monev_user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <button type="submit"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                Tambah Penugasan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Satker</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tim Monev</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Perwakilan Satker</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($satkers as $satker)
                                @php $items = $penugasans->get($satker->id, collect()); @endphp
                                <tr>
                                    <td class="px-4 py-3 align-top">
                                        {{ $satker->kode_satker }} — {{ $satker->nama_satker }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @forelse ($items as $penugasan)
                                            <div class="flex items-center justify-between gap-3 py-1">
                                                <span>{{ $penugasan->monevUser->name ?? '-' }}</span>
                                                <form method="POST"
                                                      action="{{ route('admin.penugasan.destroy', $penugasan) }}"
                                                      onsubmit="return confirm('Hapus penugasan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @empty
                                            <span class="text-gray-400 italic">Belum ditugaskan</span>
                                        @endforelse
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        @php
                                            $wakil = $perwakilans->get($satker->id, collect());
                                            $satkerUsers = $satker->users()->where('role', 'satker')->orderBy('name')->get();
                                        @endphp

                                        @forelse ($wakil as $p)
                                            <div class="flex items-center justify-between gap-3 py-1">
                                                <span>
                                                    {{ $p->urutan }}. {{ $p->user->name ?? '-' }}
                                                </span>
                                                <form method="POST"
                                                      action="{{ route('admin.penugasan.perwakilan.destroy', $p) }}"
                                                      onsubmit="return confirm('Hapus perwakilan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @empty
                                            <span class="text-gray-400 italic">Belum ada perwakilan</span>
                                        @endforelse

                                        <form method="POST"
                                              action="{{ route('admin.penugasan.perwakilan.store') }}"
                                              class="mt-2 flex flex-wrap items-end gap-2">
                                            @csrf
                                            <input type="hidden" name="periode_id" value="{{ $selectedPeriode->id }}">
                                            <input type="hidden" name="satker_id" value="{{ $satker->id }}">

                                            <select name="urutan"
                                                    class="rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                            </select>

                                            <select name="user_id"
                                                    class="rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @forelse ($satkerUsers as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                @empty
                                                    <option value="">Belum ada user satker</option>
                                                @endforelse
                                            </select>

                                            <button type="submit"
                                                    class="rounded-md bg-indigo-600 px-3 py-1 text-xs font-medium text-white hover:bg-indigo-700">
                                                Simpan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada data satker.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    Belum ada periode review. Silakan buat periode terlebih dahulu.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
