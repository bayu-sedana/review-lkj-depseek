<?php

namespace App\Services;

use App\Models\LkjSubmission;
use App\Models\PeriodeReview;
use App\Models\Satker;
use Illuminate\Support\Collection;

class DashboardStatistikService
{
    /**
     * Build the global statistics for the given period.
     *
     * @return array<string, int>
     */
    public function statistik(?PeriodeReview $periode): array
    {
        $totalSatker = Satker::count();

        if (! $periode) {
            return [
                'total_satker' => $totalSatker,
                'belum_upload' => $totalSatker,
                'sedang_review' => 0,
                'proses_revisi' => 0,
                'selesai' => 0,
            ];
        }

        $submissions = LkjSubmission::where('periode_id', $periode->id)->get();

        $sedangReview = $submissions->where('status_keseluruhan', 'proses_review')->count();
        $prosesRevisi = $submissions->where('status_keseluruhan', 'perlu_revisi')->count();
        $selesai = $submissions->where('status_keseluruhan', 'selesai')->count();

        return [
            'total_satker' => $totalSatker,
            'belum_upload' => max($totalSatker - $submissions->count(), 0),
            'sedang_review' => $sedangReview,
            'proses_revisi' => $prosesRevisi,
            'selesai' => $selesai,
        ];
    }

    /**
     * Determine whether the given period is past its revision deadline.
     */
    public function isLewatDeadline(?PeriodeReview $periode): bool
    {
        if (! $periode || ! $periode->deadline_revisi) {
            return false;
        }

        return now()->greaterThan($periode->deadline_revisi);
    }

    /**
     * Get the submissions that are past the deadline and not yet finished.
     *
     * @return Collection<int, LkjSubmission>
     */
    public function submissionsLewatDeadline(?PeriodeReview $periode): Collection
    {
        if (! $periode || ! $this->isLewatDeadline($periode)) {
            return collect();
        }

        return LkjSubmission::with('satker')
            ->where('periode_id', $periode->id)
            ->where('status_keseluruhan', '!=', 'selesai')
            ->get();
    }
}
