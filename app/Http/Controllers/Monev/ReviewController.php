<?php

namespace App\Http\Controllers\Monev;

use App\Http\Controllers\Controller;
use App\Models\HasilReview;
use App\Models\LkjSubmission;
use App\Models\PenugasanMonev;
use App\Models\PeriodeReview;
use App\Models\ReviewCapaianKinerja;
use App\Models\RubrikReview;
use App\Models\SasaranKegiatan;
use App\Services\DashboardStatistikService;
use App\Services\ReviewProgressService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewProgressService $progress,
        private readonly DashboardStatistikService $statistik,
    ) {
    }

    /**
     * Display the list of satkers assigned to the authenticated monev user.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $periodes = PeriodeReview::orderByDesc('tahun_review')
            ->orderByDesc('tahun_lkj')
            ->get();

        $selectedPeriode = null;

        if ($request->filled('periode_id')) {
            $selectedPeriode = $periodes->firstWhere('id', (int) $request->input('periode_id'));
        }

        $selectedPeriode ??= $periodes->first();

        $penugasans = collect();

        if ($selectedPeriode) {
            $penugasans = PenugasanMonev::with(['satker', 'periode'])
                ->where('periode_id', $selectedPeriode->id)
                ->where('monev_user_id', $user->id)
                ->get();
        }

        $isLewatDeadline = $this->statistik->isLewatDeadline($selectedPeriode);

        return view('monev.dashboard', compact(
            'periodes',
            'selectedPeriode',
            'penugasans',
            'isLewatDeadline'
        ));
    }

    /**
     * Display the detail review page for a satker with the 3 review tabs.
     */
    public function show(Request $request, PenugasanMonev $penugasan): View
    {
        $this->authorizePenugasan($request, $penugasan);

        $penugasan->load(['satker', 'periode']);

        $submission = LkjSubmission::with(['dokumens', 'beritaAcara'])
            ->where('periode_id', $penugasan->periode_id)
            ->where('satker_id', $penugasan->satker_id)
            ->first();

        $dokumen = $submission?->dokumenTerakhir();

        $rubrikFormat = RubrikReview::where('aspek', 'format_pelaporan')
            ->orderBy('urutan')
            ->get();

        $rubrikPengungkapan = RubrikReview::where('aspek', 'pengungkapan_informasi')
            ->orderBy('urutan')
            ->get();

        $sasarans = SasaranKegiatan::with('indikatorKinerja')
            ->where('periode_id', $penugasan->periode_id)
            ->where('satker_id', $penugasan->satker_id)
            ->orderBy('id')
            ->get();

        $hasilReviews = collect();
        $capaianKinerjas = collect();

        if ($dokumen) {
            $hasilReviews = HasilReview::where('lkj_dokumen_id', $dokumen->id)
                ->get()
                ->keyBy(fn (HasilReview $item) => $item->rubrik_id.'-'.($item->indikator_kinerja_id ?? 'null'));

            $capaianKinerjas = ReviewCapaianKinerja::where('lkj_dokumen_id', $dokumen->id)
                ->get()
                ->keyBy('indikator_kinerja_id');
        }

        $totalPoin = $submission ? $this->progress->totalPoin($submission) : 0;
        $persentase = $submission ? $this->progress->persentase($submission) : 0;
        $beritaAcara = $submission?->beritaAcara;
        $isSelesai = $submission?->status_keseluruhan === 'selesai';

        return view('monev.review.show', compact(
            'penugasan',
            'submission',
            'dokumen',
            'rubrikFormat',
            'rubrikPengungkapan',
            'sasarans',
            'hasilReviews',
            'capaianKinerjas',
            'totalPoin',
            'persentase',
            'beritaAcara',
            'isSelesai'
        ));
    }

    /**
     * Ensure the penugasan belongs to the authenticated monev user.
     */
    private function authorizePenugasan(Request $request, PenugasanMonev $penugasan): void
    {
        if ($penugasan->monev_user_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk penugasan ini.');
        }
    }
}
