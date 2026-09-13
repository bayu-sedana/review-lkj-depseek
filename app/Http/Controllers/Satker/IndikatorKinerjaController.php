<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\IndikatorKinerja;
use App\Models\SasaranKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IndikatorKinerjaController extends Controller
{
    /**
     * Store a newly created indikator kinerja.
     */
    public function store(Request $request, SasaranKegiatan $sasaran): RedirectResponse
    {
        $this->authorizeOwnership($request, $sasaran);

        $validated = $request->validate([
            'indikator_kinerja' => ['required', 'string'],
        ]);

        $sasaran->indikatorKinerja()->create($validated);

        return redirect()
            ->route('satker.sasaran.index')
            ->with('success', 'Indikator kinerja berhasil ditambahkan.');
    }

    /**
     * Update the specified indikator kinerja.
     */
    public function update(Request $request, IndikatorKinerja $indikator): RedirectResponse
    {
        $this->authorizeOwnership($request, $indikator->sasaranKegiatan);

        $validated = $request->validate([
            'indikator_kinerja' => ['required', 'string'],
        ]);

        $indikator->update($validated);

        return redirect()
            ->route('satker.sasaran.index')
            ->with('success', 'Indikator kinerja berhasil diperbarui.');
    }

    /**
     * Remove the specified indikator kinerja.
     */
    public function destroy(Request $request, IndikatorKinerja $indikator): RedirectResponse
    {
        $this->authorizeOwnership($request, $indikator->sasaranKegiatan);

        $indikator->delete();

        return redirect()
            ->route('satker.sasaran.index')
            ->with('success', 'Indikator kinerja berhasil dihapus.');
    }

    /**
     * Ensure the sasaran belongs to the authenticated user's satker.
     */
    private function authorizeOwnership(Request $request, SasaranKegiatan $sasaran): void
    {
        if ($sasaran->satker_id !== $request->user()->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk data ini.');
        }
    }
}
