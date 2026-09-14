<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeReview;
use App\Services\DashboardStatistikService;
use App\Services\ReviewProgressService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardStatistikService $statistik,
        private readonly ReviewProgressService $progress,
    ) {
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

        $progressPerSatker = $this->progressPerSatker($selectedPeriode);

        return view('admin.dashboard', compact(
            'periodes',
            'selectedPeriode',
            'statistik',
            'isLewatDeadline',
            'submissionsLewatDeadline',
            'progressPerSatker'
        ));
    }

    /**
     * Build the per-satker review progress data for the chart.
     *
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    private function progressPerSatker(?PeriodeReview $periode): array
    {
        if (! $periode) {
            return ['labels' => [], 'values' => []];
        }

        $submissions = $periode->lkjSubmissions()
            ->with('satker')
            ->get()
            ->sortBy(fn ($submission) => $submission->satker->kode_satker ?? '');

        $labels = [];
        $values = [];

        foreach ($submissions as $submission) {
            $labels[] = $submission->satker->kode_satker ?? '-';
            $values[] = $this->progress->persentase($submission);
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
