<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\LkjSubmission;
use App\Models\PeriodeReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LkjSubmissionController extends Controller
{
    /**
     * Display the LKj upload page and version history.
     */
    public function index(Request $request): View
    {
        $satker = $request->user()->satker;
        $periode = $this->resolvePeriode($request);

        $submission = null;

        if ($satker && $periode) {
            $submission = LkjSubmission::with(['dokumens.uploader'])
                ->where('satker_id', $satker->id)
                ->where('periode_id', $periode->id)
                ->first();
        }

        return view('satker.lkj.index', compact('satker', 'periode', 'submission'));
    }

    /**
     * Store a newly uploaded LKj document (V1 or subsequent versions).
     */
    public function store(Request $request): RedirectResponse
    {
        $satker = $request->user()->satker;
        $periode = $this->resolvePeriode($request);

        if (! $satker || ! $periode) {
            return redirect()
                ->route('satker.lkj.index')
                ->with('error', 'Satker atau periode aktif tidak ditemukan.');
        }

        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
        ]);

        $submission = LkjSubmission::firstOrCreate(
            [
                'periode_id' => $periode->id,
                'satker_id' => $satker->id,
            ],
            [
                'status_keseluruhan' => 'proses_review',
            ]
        );

        $versi = $submission->versiBerikutnya();

        $file = $validated['file'];
        $extension = $file->getClientOriginalExtension();
        $fileName = sprintf(
            '%s_%s_LKj_V%d.%s',
            $periode->tahun_lkj,
            $satker->kode_satker,
            $versi,
            $extension
        );

        $directory = sprintf('lkj/%s/%s', $periode->tahun_lkj, $satker->kode_satker);
        $path = $file->storeAs($directory, $fileName, 'public');

        $submission->dokumens()->create([
            'versi' => $versi,
            'file_path' => $path,
            'file_name' => $fileName,
            'diupload_oleh' => $request->user()->id,
        ]);

        if ($submission->status_keseluruhan === 'belum_upload') {
            $submission->update(['status_keseluruhan' => 'proses_review']);
        }

        return redirect()
            ->route('satker.lkj.index')
            ->with('success', "Dokumen LKj versi {$versi} berhasil diunggah.");
    }

    /**
     * Download the specified LKj document.
     */
    public function download(Request $request, \App\Models\LkjDokumen $dokumen)
    {
        $satker = $request->user()->satker;

        if (! $satker || $dokumen->submission->satker_id !== $satker->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk dokumen ini.');
        }

        if (! Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
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
