<?php

namespace App\Http\Controllers;

use App\Models\PenugasanMonev;
use App\Models\PeriodeReview;
use App\Models\PerwakilanSatker;
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
        $perwakilans = collect();

        if ($selectedPeriode) {
            $satkers = Satker::orderBy('kode_satker')->get();
            $monevUsers = User::where('role', 'monev')->orderBy('name')->get();
            $penugasans = PenugasanMonev::with(['satker', 'monevUser'])
                ->where('periode_id', $selectedPeriode->id)
                ->get()
                ->groupBy('satker_id');
            $perwakilans = PerwakilanSatker::with('user')
                ->where('periode_id', $selectedPeriode->id)
                ->orderBy('urutan')
                ->get()
                ->groupBy('satker_id');
        }

        return view('admin.penugasan.index', compact(
            'periodes',
            'selectedPeriode',
            'satkers',
            'monevUsers',
            'penugasans',
            'perwakilans'
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
     * Store a new satker representative.
     */
    public function storePerwakilan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'periode_id' => ['required', 'exists:periode_reviews,id'],
            'satker_id' => ['required', 'exists:satkers,id'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($user->role !== 'satker') {
            return redirect()
                ->route('admin.penugasan.index', ['periode_id' => $validated['periode_id']])
                ->with('error', 'User yang dipilih bukan user Satker.');
        }

        if ((int) $user->satker_id !== (int) $validated['satker_id']) {
            return redirect()
                ->route('admin.penugasan.index', ['periode_id' => $validated['periode_id']])
                ->with('error', 'User tersebut tidak terdaftar pada satker yang dipilih.');
        }

        $existing = PerwakilanSatker::where('periode_id', $validated['periode_id'])
            ->where('satker_id', $validated['satker_id'])
            ->get();

        if ($existing->contains('user_id', $user->id)) {
            return redirect()
                ->route('admin.penugasan.index', ['periode_id' => $validated['periode_id']])
                ->with('error', 'User tersebut sudah terdaftar sebagai perwakilan satker.');
        }

        if ($existing->count() >= 2) {
            return redirect()
                ->route('admin.penugasan.index', ['periode_id' => $validated['periode_id']])
                ->with('error', 'Maksimal 2 perwakilan satker per periode.');
        }

        $urutan = $existing->contains('urutan', 1) ? 2 : 1;

        PerwakilanSatker::create([
            'periode_id' => $validated['periode_id'],
            'satker_id' => $validated['satker_id'],
            'user_id' => $validated['user_id'],
            'urutan' => $urutan,
        ]);

        return redirect()
            ->route('admin.penugasan.index', ['periode_id' => $validated['periode_id']])
            ->with('success', 'Perwakilan satker berhasil disimpan.');
    }

    /**
     * Remove the specified satker representative from storage.
     */
    public function destroyPerwakilan(PerwakilanSatker $perwakilan): RedirectResponse
    {
        $periodeId = $perwakilan->periode_id;
        $perwakilan->delete();

        return redirect()
            ->route('admin.penugasan.index', ['periode_id' => $periodeId])
            ->with('success', 'Perwakilan satker berhasil dihapus.');
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
