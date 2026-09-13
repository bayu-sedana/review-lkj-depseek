<?php

namespace App\Http\Controllers\Monev;

use App\Http\Controllers\Controller;
use App\Models\HasilReview;
use App\Models\LkjSubmission;
use App\Models\PenugasanMonev;
use App\Models\RubrikReview;
use App\Services\RevisionNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilReviewController extends Controller
{
    public function __construct(private readonly RevisionNotificationService $revision)
    {
    }
    /**
     * Save the Aspek 1 (Format Pelaporan) reviews for a submission.
     */
    public function storeAspek1(Request $request, PenugasanMonev $penugasan): RedirectResponse
    {
        $this->authorizePenugasan($request, $penugasan);

        $submission = $this->resolveSubmission($penugasan);
        $dokumen = $submission?->dokumenTerakhir();

        if (! $dokumen) {
            return back()->with('error', 'Satker belum mengunggah dokumen LKj.');
        }

        $validated = $request->validate([
            'reviews' => ['required', 'array'],
            'reviews.*.rubrik_id' => ['required', 'exists:rubrik_reviews,id'],
            'reviews.*.uraian_hasil_review' => ['nullable', 'string'],
            'reviews.*.status' => ['nullable', 'in:Sesuai,Belum Sesuai'],
            'reviews.*.catatan_perbaikan' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $dokumen, $request) {
            foreach ($validated['reviews'] as $row) {
                $status = $row['status'] ?? null;

                HasilReview::updateOrCreate(
                    [
                        'lkj_dokumen_id' => $dokumen->id,
                        'rubrik_id' => $row['rubrik_id'],
                        'indikator_kinerja_id' => null,
                    ],
                    [
                        'uraian_hasil_review' => $row['uraian_hasil_review'] ?? null,
                        'status' => $status,
                        'catatan_perbaikan' => $status === 'Belum Sesuai'
                            ? ($row['catatan_perbaikan'] ?? null)
                            : null,
                        'direview_oleh' => $request->user()->id,
                    ]
                );
            }
        });

        $this->revision->evaluate($submission);

        return back()->with('success', 'Review Aspek 1 berhasil disimpan.');
    }

    /**
     * Save the Aspek 3 (Pengungkapan Informasi) reviews for a submission.
     */
    public function storeAspek3(Request $request, PenugasanMonev $penugasan): RedirectResponse
    {
        $this->authorizePenugasan($request, $penugasan);

        $submission = $this->resolveSubmission($penugasan);
        $dokumen = $submission?->dokumenTerakhir();

        if (! $dokumen) {
            return back()->with('error', 'Satker belum mengunggah dokumen LKj.');
        }

        $validated = $request->validate([
            'reviews' => ['required', 'array'],
            'reviews.*.rubrik_id' => ['required', 'exists:rubrik_reviews,id'],
            'reviews.*.indikator_kinerja_id' => ['required', 'exists:indikator_kinerjas,id'],
            'reviews.*.uraian_hasil_review' => ['nullable', 'string'],
            'reviews.*.status' => ['nullable', 'in:Sesuai,Belum Sesuai'],
            'reviews.*.catatan_perbaikan' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $dokumen, $request) {
            foreach ($validated['reviews'] as $row) {
                $status = $row['status'] ?? null;

                HasilReview::updateOrCreate(
                    [
                        'lkj_dokumen_id' => $dokumen->id,
                        'rubrik_id' => $row['rubrik_id'],
                        'indikator_kinerja_id' => $row['indikator_kinerja_id'],
                    ],
                    [
                        'uraian_hasil_review' => $row['uraian_hasil_review'] ?? null,
                        'status' => $status,
                        'catatan_perbaikan' => $status === 'Belum Sesuai'
                            ? ($row['catatan_perbaikan'] ?? null)
                            : null,
                        'direview_oleh' => $request->user()->id,
                    ]
                );
            }
        });

        $this->revision->evaluate($submission);

        return back()->with('success', 'Review Aspek 3 berhasil disimpan.');
    }

    /**
     * Resolve the submission for the given penugasan.
     */
    private function resolveSubmission(PenugasanMonev $penugasan): ?LkjSubmission
    {
        return LkjSubmission::with('dokumens')
            ->where('periode_id', $penugasan->periode_id)
            ->where('satker_id', $penugasan->satker_id)
            ->first();
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
