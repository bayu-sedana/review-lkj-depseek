<?php

namespace App\Services;

use App\Models\HasilReview;
use App\Models\LkjSubmission;
use App\Models\ReviewCapaianKinerja;
use App\Models\SasaranKegiatan;

class ReviewProgressService
{
    /**
     * Number of per-document rubric points (Aspek 1).
     */
    public const POIN_PER_DOKUMEN = 8;

    /**
     * Number of per-indicator rubric points (Aspek 3).
     */
    public const POIN_PER_INDIKATOR = 8;

    /**
     * Calculate the total number of review points for a submission.
     *
     * Total Poin = 8 + (8 x Jumlah Indikator Kinerja)
     */
    public function totalPoin(LkjSubmission $submission): int
    {
        return self::POIN_PER_DOKUMEN
            + (self::POIN_PER_INDIKATOR * $this->jumlahIndikator($submission));
    }

    /**
     * Count the indikator kinerja belonging to the submission's satker and period.
     */
    public function jumlahIndikator(LkjSubmission $submission): int
    {
        return SasaranKegiatan::where('satker_id', $submission->satker_id)
            ->where('periode_id', $submission->periode_id)
            ->withCount('indikatorKinerja')
            ->get()
            ->sum('indikator_kinerja_count');
    }

    /**
     * Calculate the current progress percentage (0-100).
     *
     * Progres = (Jumlah Poin 'Sesuai' / Total Poin) x 100
     */
    public function persentase(LkjSubmission $submission): int
    {
        $total = $this->totalPoin($submission);

        if ($total === 0) {
            return 0;
        }

        $poinSesuai = $this->poinSesuai($submission);

        return (int) round(($poinSesuai / $total) * 100);
    }

    /**
     * Count the points already marked as 'Sesuai' or 'Sinkron' for the latest document.
     */
    public function poinSesuai(LkjSubmission $submission): int
    {
        $dokumen = $submission->dokumenTerakhir();

        if (! $dokumen) {
            return 0;
        }

        $poinAspek1Dan3 = HasilReview::where('lkj_dokumen_id', $dokumen->id)
            ->where('status', 'Sesuai')
            ->count();

        $poinAspek2 = ReviewCapaianKinerja::where('lkj_dokumen_id', $dokumen->id)
            ->where('is_sinkron', true)
            ->count();

        return $poinAspek1Dan3 + $poinAspek2;
    }
}
