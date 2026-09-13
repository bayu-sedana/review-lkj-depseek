<?php

namespace App\Http\Controllers\Monev;

use App\Http\Controllers\Controller;
use App\Models\BeritaAcara;
use App\Models\LkjSubmission;
use App\Models\PenugasanMonev;
use App\Services\BeritaAcaraGeneratorService;
use App\Services\ReviewProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BeritaAcaraController extends Controller
{
    public function __construct(
        private readonly ReviewProgressService $progress,
        private readonly BeritaAcaraGeneratorService $generator,
    ) {
    }

    /**
     * Mark the review as finished (only allowed at 100% progress).
     */
    public function tandaiSelesai(Request $request, PenugasanMonev $penugasan): RedirectResponse
    {
        $this->authorizePenugasan($request, $penugasan);

        $submission = $this->resolveSubmission($penugasan);

        if (! $submission) {
            return back()->with('error', 'Satker belum mengunggah dokumen LKj.');
        }

        if ($this->progress->persentase($submission) < 100) {
            return back()->with('error', 'Progres review belum mencapai 100%.');
        }

        $submission->update([
            'status_keseluruhan' => 'selesai',
            'tanggal_selesai' => now(),
        ]);

        return back()->with('success', 'Review berhasil ditandai selesai.');
    }

    /**
     * Generate the Berita Acara .docx from the template.
     */
    public function generate(Request $request, PenugasanMonev $penugasan): RedirectResponse
    {
        $this->authorizePenugasan($request, $penugasan);

        $submission = $this->resolveSubmission($penugasan);

        if (! $submission) {
            return back()->with('error', 'Satker belum mengunggah dokumen LKj.');
        }

        if ($submission->status_keseluruhan !== 'selesai') {
            return back()->with('error', 'Tandai review sebagai selesai terlebih dahulu.');
        }

        try {
            $this->generator->generate($submission, $request->user()->id);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Berita Acara berhasil di-generate.');
    }

    /**
     * Download the generated Berita Acara .docx.
     */
    public function downloadWord(Request $request, PenugasanMonev $penugasan): StreamedResponse
    {
        $this->authorizePenugasan($request, $penugasan);

        $submission = $this->resolveSubmission($penugasan);
        $beritaAcara = $submission?->beritaAcara;

        if (! $beritaAcara || ! $beritaAcara->hasWord()) {
            abort(404, 'Berita Acara belum di-generate.');
        }

        if (! Storage::disk('local')->exists($beritaAcara->file_word_path)) {
            abort(404, 'File Berita Acara tidak ditemukan.');
        }

        return Storage::disk('local')->download(
            $beritaAcara->file_word_path,
            basename($beritaAcara->file_word_path)
        );
    }

    /**
     * Upload the scanned Berita Acara PDF.
     */
    public function uploadPdf(Request $request, PenugasanMonev $penugasan): RedirectResponse
    {
        $this->authorizePenugasan($request, $penugasan);

        $submission = $this->resolveSubmission($penugasan);

        if (! $submission) {
            return back()->with('error', 'Satker belum mengunggah dokumen LKj.');
        }

        $validated = $request->validate([
            'file_pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $beritaAcara = $submission->beritaAcara;

        if (! $beritaAcara) {
            return back()->with('error', 'Generate Berita Acara terlebih dahulu.');
        }

        $directory = sprintf(
            'berita_acara/%s/%s',
            $submission->periode->tahun_lkj ?? 'unknown',
            $submission->satker->kode_satker ?? 'unknown'
        );

        $fileName = sprintf(
            'BA_%s_%s_scan.pdf',
            $submission->periode->tahun_lkj ?? 'unknown',
            $submission->satker->kode_satker ?? 'unknown'
        );

        $path = $validated['file_pdf']->storeAs($directory, $fileName, 'local');

        $beritaAcara->update([
            'file_pdf_path' => $path,
            'diupload_oleh' => $request->user()->id,
        ]);

        return back()->with('success', 'Berita Acara hasil scan berhasil diunggah.');
    }

    /**
     * Resolve the submission for the given penugasan.
     */
    private function resolveSubmission(PenugasanMonev $penugasan): ?LkjSubmission
    {
        return LkjSubmission::with(['dokumens', 'beritaAcara'])
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
