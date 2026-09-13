<?php

namespace App\Http\Controllers\Monev;

use App\Http\Controllers\Controller;
use App\Models\LkjSubmission;
use App\Models\PenugasanMonev;
use App\Models\ReviewCapaianKinerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewCapaianKinerjaController extends Controller
{
    /**
     * Save the Aspek 2 (Capaian Kinerja) reviews for a submission.
     */
    public function storeAspek2(Request $request, PenugasanMonev $penugasan): RedirectResponse
    {
        $this->authorizePenugasan($request, $penugasan);

        $submission = $this->resolveSubmission($penugasan);
        $dokumen = $submission?->dokumenTerakhir();

        if (! $dokumen) {
            return back()->with('error', 'Satker belum mengunggah dokumen LKj.');
        }

        $validated = $request->validate([
            'reviews' => ['required', 'array'],
            'reviews.*.indikator_kinerja_id' => ['required', 'exists:indikator_kinerjas,id'],
            'reviews.*.nilai_exec_summary' => ['nullable', 'numeric'],
            'reviews.*.nilai_bab_3' => ['nullable', 'numeric'],
            'reviews.*.nilai_bab_4' => ['nullable', 'numeric'],
            'reviews.*.nilai_aplikasi_kinerjaku' => ['nullable', 'numeric'],
            'reviews.*.nilai_data_dukung' => ['nullable', 'numeric'],
            'reviews.*.catatan_perbaikan' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $dokumen, $request) {
            foreach ($validated['reviews'] as $row) {
                $nilai = [
                    $row['nilai_exec_summary'] ?? null,
                    $row['nilai_bab_3'] ?? null,
                    $row['nilai_bab_4'] ?? null,
                    $row['nilai_aplikasi_kinerjaku'] ?? null,
                    $row['nilai_data_dukung'] ?? null,
                ];

                $isSinkron = $this->hitungSinkron($nilai);

                ReviewCapaianKinerja::updateOrCreate(
                    [
                        'lkj_dokumen_id' => $dokumen->id,
                        'indikator_kinerja_id' => $row['indikator_kinerja_id'],
                    ],
                    [
                        'nilai_exec_summary' => $nilai[0],
                        'nilai_bab_3' => $nilai[1],
                        'nilai_bab_4' => $nilai[2],
                        'nilai_aplikasi_kinerjaku' => $nilai[3],
                        'nilai_data_dukung' => $nilai[4],
                        'is_sinkron' => $isSinkron,
                        'catatan_perbaikan' => $isSinkron
                            ? null
                            : ($row['catatan_perbaikan'] ?? null),
                        'direview_oleh' => $request->user()->id,
                    ]
                );
            }
        });

        return back()->with('success', 'Review Aspek 2 berhasil disimpan.');
    }

    /**
     * Determine whether all five values are present and equal.
     *
     * @param  array<int, mixed>  $nilai
     */
    private function hitungSinkron(array $nilai): bool
    {
        $terisi = array_filter($nilai, fn ($value) => $value !== null && $value !== '');

        if (count($terisi) < 5) {
            return false;
        }

        return count(array_unique(array_map('strval', $terisi))) === 1;
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
