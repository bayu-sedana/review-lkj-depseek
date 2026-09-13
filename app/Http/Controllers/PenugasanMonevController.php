<?php

namespace App\Http\Controllers;

use App\Models\PenugasanMonev;
use App\Models\PeriodeReview;
use App\Models\Satker;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenugasanMonevController extends Controller
{
    /**
     * Display the monev assignment management page.
     */
    public function index(Request $request): View
    {
        $periodes = PeriodeReview::orderByDesc('tahun_review')->orderByDesc('tahun_lkj')->get();

        $selectedPeriode = null;

        if ($request->filled('periode_id')) {
            $selectedPeriode = $periodes->firstWhere('id', (int) $request->input('periode_id'));
        }

        $selectedPeriode ??= $periodes->first();

        $satkers = collect();
        $monevUsers = collect();
        $penugasans = collect();

        if ($selectedPeriode) {
            $satkers = Satker::orderBy('kode_satker')->get();
            $monevUsers = User::where('role', 'monev')->orderBy('name')->get();
            $penugasans = PenugasanMonev::with(['satker', 'monevUser'])
                ->where('periode_id', $selectedPeriode->id)
                ->get()
                ->groupBy('satker_id');
        }

        return view('admin.penugasan.index', compact(
            'periodes',
            'selectedPeriode',
            'satkers',
            'monevUsers',
            'penugasans'
        ));
    }

    /**
     * Store a new monev assignment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'periode_id' => ['required', 'exists:periode_reviews,id'],
            'satker_id' => ['required', 'exists:satkers,id'],
            'monev_user_id' => ['required', 'exists:users,id'],
        ]);

        $monevUser = User::findOrFail($validated['monev_user_id']);

        if ($monevUser->role !== 'monev') {
            return redirect()
                ->route('admin.penugasan.index', ['periode_id' => $validated['periode_id']])
                ->with('error', 'User yang dipilih bukan Tim Monev.');
        }

        PenugasanMonev::firstOrCreate($validated);

        return redirect()
            ->route('admin.penugasan.index', ['periode_id' => $validated['periode_id']])
            ->with('success', 'Penugasan monev berhasil ditambahkan.');
    }

    /**
     * Remove the specified monev assignment from storage.
     */
    public function destroy(PenugasanMonev $penugasan): RedirectResponse
    {
        $periodeId = $penugasan->periode_id;
        $penugasan->delete();

        return redirect()
            ->route('admin.penugasan.index', ['periode_id' => $periodeId])
            ->with('success', 'Penugasan monev berhasil dihapus.');
    }
}
