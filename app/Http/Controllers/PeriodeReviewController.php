<?php

namespace App\Http\Controllers;

use App\Models\PeriodeReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PeriodeReviewController extends Controller
{
    /**
     * Display a listing of the review periods.
     */
    public function index(): View
    {
        $periodes = PeriodeReview::withCount('penugasanMonev')
            ->orderByDesc('tahun_review')
            ->orderByDesc('tahun_lkj')
            ->paginate(15);

        return view('admin.periodes.index', compact('periodes'));
    }

    /**
     * Show the form for creating a new review period.
     */
    public function create(): View
    {
        return view('admin.periodes.create');
    }

    /**
     * Store a newly created review period in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_lkj' => ['required', 'integer', 'min:2000', 'max:2100'],
            'tahun_review' => ['required', 'integer', 'min:2000', 'max:2100'],
            'deadline_revisi' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['aktif', 'ditutup'])],
        ]);

        PeriodeReview::create($validated);

        return redirect()
            ->route('admin.periodes.index')
            ->with('success', 'Periode review berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified review period.
     */
    public function edit(PeriodeReview $periode): View
    {
        return view('admin.periodes.edit', compact('periode'));
    }

    /**
     * Update the specified review period in storage.
     */
    public function update(Request $request, PeriodeReview $periode): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_lkj' => ['required', 'integer', 'min:2000', 'max:2100'],
            'tahun_review' => ['required', 'integer', 'min:2000', 'max:2100'],
            'deadline_revisi' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['aktif', 'ditutup'])],
        ]);

        $periode->update($validated);

        return redirect()
            ->route('admin.periodes.index')
            ->with('success', 'Periode review berhasil diperbarui.');
    }

    /**
     * Remove the specified review period from storage.
     */
    public function destroy(PeriodeReview $periode): RedirectResponse
    {
        if ($periode->penugasanMonev()->exists()) {
            return redirect()
                ->route('admin.periodes.index')
                ->with('error', 'Periode tidak dapat dihapus karena masih memiliki penugasan monev.');
        }

        $periode->delete();

        return redirect()
            ->route('admin.periodes.index')
            ->with('success', 'Periode review berhasil dihapus.');
    }
}
