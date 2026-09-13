<?php

namespace App\Services;

use App\Mail\RevisionNotificationMail;
use App\Models\HasilReview;
use App\Models\LkjSubmission;
use App\Models\Notification;
use App\Models\ReviewCapaianKinerja;
use Illuminate\Support\Facades\Mail;

class RevisionNotificationService
{
    /**
     * Evaluate the latest document of a submission and notify the satker
     * if there are items requiring revision.
     */
    public function evaluate(LkjSubmission $submission): int
    {
        $dokumen = $submission->dokumenTerakhir();

        if (! $dokumen) {
            return 0;
        }

        $jumlahPerbaikan = $this->jumlahPerbaikan($dokumen->id);

        if ($jumlahPerbaikan > 0) {
            $submission->update(['status_keseluruhan' => 'perlu_revisi']);

            $this->kirimNotifikasi($submission, $jumlahPerbaikan);
        } else {
            $submission->update(['status_keseluruhan' => 'proses_review']);
        }

        return $jumlahPerbaikan;
    }

    /**
     * Count the items marked as needing revision for a document.
     */
    public function jumlahPerbaikan(int $dokumenId): int
    {
        $aspek1Dan3 = HasilReview::where('lkj_dokumen_id', $dokumenId)
            ->where('status', 'Belum Sesuai')
            ->count();

        $aspek2 = ReviewCapaianKinerja::where('lkj_dokumen_id', $dokumenId)
            ->where('is_sinkron', false)
            ->whereNotNull('nilai_exec_summary')
            ->count();

        return $aspek1Dan3 + $aspek2;
    }

    /**
     * Send the revision notification email to the satker operator(s).
     */
    protected function kirimNotifikasi(LkjSubmission $submission, int $jumlahPerbaikan): void
    {
        $submission->loadMissing(['satker.users', 'periode']);

        $operators = $submission->satker?->users()
            ->where('role', 'satker')
            ->get() ?? collect();

        $title = 'Revisi LKj Diperlukan';
        $message = sprintf(
            'Terdapat %d item yang perlu diperbaiki pada LKj %s periode %s/%s. Silakan lengkapi tanggapan perbaikan dan unggah dokumen revisi.',
            $jumlahPerbaikan,
            $submission->satker->nama_satker ?? '-',
            $submission->periode->tahun_lkj ?? '-',
            $submission->periode->tahun_review ?? '-'
        );

        foreach ($operators as $operator) {
            Notification::create([
                'user_id' => $operator->id,
                'title' => $title,
                'message' => $message,
                'is_read' => false,
            ]);

            Mail::to($operator->email)->send(
                new RevisionNotificationMail($submission, $jumlahPerbaikan)
            );
        }
    }
}
