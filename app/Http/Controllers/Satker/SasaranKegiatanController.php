<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\PeriodeReview;
use App\Models\SasaranKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SasaranKegiatanController extends Controller
{
    /**
     * Display the sasaran kegiatan list for the active period.
     */
    public function index(Request $request): View
    {
        $satker = $request->user()->satker;
        $periode = $this->resolvePeriode($request);

        $sasarans = collect();

        if ($satker && $periode) {
            $sasarans = SasaranKegiatan::with('indikatorKinerja')
                ->where('satker_id', $satker->id)
                ->where('periode_id', $periode->id)
                ->orderBy('id')
                ->get();
        }

        return view('satker.sasaran.index', compact('satker', 'periode', 'sasarans'));
    }

    /**
     * Store a newly created sasaran kegiatan.
     */
    public function store(Request $request): RedirectResponse
    {
        $satker = $request->user()->satker;
        $periode = $this->resolvePeriode($request);

        if (! $satker || ! $periode) {
            return redirect()
                ->route('satker.sasaran.index')
                ->with('error', 'Satker atau periode aktif tidak ditemukan.');
        }

        $validated = $request->validate([
            'sasaran_kegiatan' => ['required', 'string'],
        ]);

        SasaranKegiatan::create([
            'periode_id' => $periode->id,
            'satker_id' => $satker->id,
            'sasaran_kegiatan' => $validated['sasaran_kegiatan'],
        ]);

        return redirect()
            ->route('satker.sasaran.index')
            ->with('success', 'Sasaran kegiatan berhasil ditambahkan.');
    }

    /**
     * Update the specified sasaran kegiatan.
     */
    public function update(Request $request, SasaranKegiatan $sasaran): RedirectResponse
    {
        $this->authorizeOwnership($request, $sasaran);

        $validated = $request->validate([
            'sasaran_kegiatan' => ['required', 'string'],
        ]);

        $sasaran->update($validated);

        return redirect()
            ->route('satker.sasaran.index')
            ->with('success', 'Sasaran kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified sasaran kegiatan.
     */
    public function destroy(Request $request, SasaranKegiatan $sasaran): RedirectResponse
    {
        $this->authorizeOwnership($request, $sasaran);

        $sasaran->delete();

        return redirect()
            ->route('satker.sasaran.index')
            ->with('success', 'Sasaran kegiatan berhasil dihapus.');
    }

    /**
     * Resolve the period to work with (from query string or the active one).
     */
    private function resolvePeriode(Request $request): ?PeriodeReview
    {
        if ($request->filled('periode_id')) {
            return PeriodeReview::find($request->input('periode_id'));
        }

        return PeriodeReview::where('status', 'aktif')
            ->orderByDesc('tahun_review')
            ->first();
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
