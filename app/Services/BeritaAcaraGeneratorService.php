<?php

namespace App\Services;

use App\Models\BeritaAcara;
use App\Models\LkjSubmission;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class BeritaAcaraGeneratorService
{
    /**
     * Path to the .docx template, relative to the storage/app directory.
     */
    public const TEMPLATE_PATH = 'templates/berita_acara_template.docx';

    /**
     * Generate the Berita Acara .docx for the given submission.
     */
    public function generate(LkjSubmission $submission, int $userId): BeritaAcara
    {
        $submission->loadMissing(['satker', 'periode']);

        $templatePath = Storage::disk('local')->path(self::TEMPLATE_PATH);

        if (! file_exists($templatePath)) {
            throw new \RuntimeException(
                'Template Berita Acara tidak ditemukan di '.self::TEMPLATE_PATH
            );
        }

        $template = new TemplateProcessor($templatePath);

        $template->setValues([
            'nama_satker' => $submission->satker->nama_satker ?? '-',
            'kode_satker' => $submission->satker->kode_satker ?? '-',
            'tahun_lkj' => (string) ($submission->periode->tahun_lkj ?? '-'),
            'tahun_review' => (string) ($submission->periode->tahun_review ?? '-'),
            'tanggal_selesai' => now()->translatedFormat('d F Y'),
            'jumlah_indikator' => (string) $this->jumlahIndikator($submission),
        ]);

        $directory = sprintf(
            'berita_acara/%s/%s',
            $submission->periode->tahun_lkj ?? 'unknown',
            $submission->satker->kode_satker ?? 'unknown'
        );

        $fileName = sprintf(
            'BA_%s_%s.docx',
            $submission->periode->tahun_lkj ?? 'unknown',
            $submission->satker->kode_satker ?? 'unknown'
        );

        $relativePath = $directory.'/'.$fileName;

        Storage::disk('local')->makeDirectory($directory);

        $template->saveAs(Storage::disk('local')->path($relativePath));

        return BeritaAcara::updateOrCreate(
            ['lkj_submission_id' => $submission->id],
            [
                'file_word_path' => $relativePath,
                'diupload_oleh' => $userId,
            ]
        );
    }

    /**
     * Count the indikator kinerja for the submission's satker and period.
     */
    protected function jumlahIndikator(LkjSubmission $submission): int
    {
        return \App\Models\SasaranKegiatan::where('satker_id', $submission->satker_id)
            ->where('periode_id', $submission->periode_id)
            ->withCount('indikatorKinerja')
            ->get()
            ->sum('indikator_kinerja_count');
    }
}
