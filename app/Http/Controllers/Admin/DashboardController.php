<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeReview;
use App\Services\DashboardStatistikService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardStatistikService $statistik)
    {
    }

    /**
     * Display the global admin dashboard.
     */
    public function index(Request $request): View
    {
        $periodes = PeriodeReview::orderByDesc('tahun_review')
            ->orderByDesc('tahun_lkj')
            ->get();

        $selectedPeriode = null;

        if ($request->filled('periode_id')) {
            $selectedPeriode = $periodes->firstWhere('id', (int) $request->input('periode_id'));
        }

        $selectedPeriode ??= $periodes->first();

        $statistik = $this->statistik->statistik($selectedPeriode);
        $isLewatDeadline = $this->statistik->isLewatDeadline($selectedPeriode);
        $submissionsLewatDeadline = $this->statistik->submissionsLewatDeadline($selectedPeriode);

        return view('admin.dashboard', compact(
            'periodes',
            'selectedPeriode',
            'statistik',
            'isLewatDeadline',
            'submissionsLewatDeadline'
        ));
    }
}
