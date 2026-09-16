<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\HasilReview;
use App\Models\LkjSubmission;
use App\Models\PeriodeReview;
use App\Models\ReviewCapaianKinerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RevisiController extends Controller
{
    /**
     * Display the list of items requiring revision for the active period.
     */
    public function index(Request $request): View
    {
        $satker = $request->user()->satker;
        $periode = $this->resolvePeriode($request);

        $submission = null;
        $dokumen = null;
        $hasilReviews = collect();
        $capaianKinerjas = collect();
        $aspek1 = collect();
        $aspek3 = collect();
        $jumlahPerbaikan = 0;
        $jumlahBelumDitanggapi = 0;

        if ($satker && $periode) {
            $submission = LkjSubmission::with(['dokumens'])
                ->where('satker_id', $satker->id)
                ->where('periode_id', $periode->id)
                ->first();

            $dokumen = $submission?->dokumenTerakhir();

            if ($dokumen) {
                $hasilReviews = HasilReview::with(['rubrik', 'indikatorKinerja'])
                    ->where('lkj_dokumen_id', $dokumen->id)
                    ->where('status', 'Belum Sesuai')
                    ->get();

                $capaianKinerjas = ReviewCapaianKinerja::with('indikatorKinerja')
                    ->where('lkj_dokumen_id', $dokumen->id)
                    ->where('is_sinkron', false)
                    ->whereNotNull('nilai_exec_summary')
                    ->get();

                $aspek1 = $hasilReviews->filter(
                    fn (HasilReview $item) => ($item->rubrik->aspek ?? null) === 'Aspek 1'
                )->values();

                $aspek3 = $hasilReviews->filter(
                    fn (HasilReview $item) => ($item->rubrik->aspek ?? null) === 'Aspek 3'
                )->values();

                $jumlahPerbaikan = $hasilReviews->count() + $capaianKinerjas->count();

                $jumlahBelumDitanggapi = $hasilReviews
                    ->filter(fn (HasilReview $item) => blank($item->tanggapan_perbaikan_satker))
                    ->count()
                    + $capaianKinerjas
                        ->filter(fn (ReviewCapaianKinerja $item) => blank($item->tanggapan_perbaikan_satker))
                        ->count();
            }
        }

        return view('satker.revisi.index', compact(
            'satker',
            'periode',
            'submission',
            'dokumen',
            'hasilReviews',
            'capaianKinerjas',
            'aspek1',
            'aspek3',
            'jumlahPerbaikan',
            'jumlahBelumDitanggapi'
        ));
    }

    /**
     * Save the satker's tanggapan for the given revision items.
     */
    public function store(Request $request): RedirectResponse
    {
        $satker = $request->user()->satker;

        if (! $satker) {
            return redirect()
                ->route('satker.revisi.index')
                ->with('error', 'Akun Anda belum terhubung ke Satker manapun.');
        }

        $validated = $request->validate([
            'hasil_reviews' => ['nullable', 'array'],
            'hasil_reviews.*.id' => ['required', 'exists:hasil_reviews,id'],
            'hasil_reviews.*.tanggapan_perbaikan_satker' => ['nullable', 'string'],

            'capaian_kinerjas' => ['nullable', 'array'],
            'capaian_kinerjas.*.id' => ['required', 'exists:review_capaian_kinerjas,id'],
            'capaian_kinerjas.*.tanggapan_perbaikan_satker' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $satker) {
            foreach ($validated['hasil_reviews'] ?? [] as $row) {
                $hasil = HasilReview::find($row['id']);

                if ($hasil && $this->milikSatker($hasil->dokumen, $satker->id)) {
                    $hasil->update([
                        'tanggapan_perbaikan_satker' => $row['tanggapan_perbaikan_satker'] ?? null,
                    ]);
                }
            }

            foreach ($validated['capaian_kinerjas'] ?? [] as $row) {
                $capaian = ReviewCapaianKinerja::find($row['id']);

                if ($capaian && $this->milikSatker($capaian->dokumen, $satker->id)) {
                    $capaian->update([
                        'tanggapan_perbaikan_satker' => $row['tanggapan_perbaikan_satker'] ?? null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('satker.revisi.index')
            ->with('success', 'Tanggapan perbaikan berhasil disimpan.');
    }

    /**
     * Determine whether the given document belongs to the satker.
     */
    private function milikSatker(?\App\Models\LkjDokumen $dokumen, int $satkerId): bool
    {
        return $dokumen && $dokumen->submission?->satker_id === $satkerId;
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
}
