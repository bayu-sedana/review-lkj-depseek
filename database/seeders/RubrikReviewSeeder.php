<?php

namespace Database\Seeders;

use App\Models\RubrikReview;
use Illuminate\Database\Seeder;

class RubrikReviewSeeder extends Seeder
{
    /**
     * Seed the static rubric data for Aspek 1 and Aspek 3.
     */
    public function run(): void
    {
        $aspek1 = [
            ['bagian_laporan' => 'Kata Pengantar', 'minimum_informasi' => 'Memuat kata pengantar yang menjelaskan latar belakang dan tujuan penyusunan LKj.'],
            ['bagian_laporan' => 'Ringkasan Eksekutif', 'minimum_informasi' => 'Memuat ringkasan capaian kinerja utama secara singkat dan jelas.'],
            ['bagian_laporan' => 'Bab 1', 'minimum_informasi' => 'Memuat pendahuluan, latar belakang, dan gambaran umum organisasi.'],
            ['bagian_laporan' => 'Bab 2', 'minimum_informasi' => 'Memuat perencanaan kinerja dan target yang ditetapkan.'],
            ['bagian_laporan' => 'Bab 3A', 'minimum_informasi' => 'Memuat capaian kinerja sasaran strategis.'],
            ['bagian_laporan' => 'Bab 3B', 'minimum_informasi' => 'Memuat capaian kinerja sasaran program/kegiatan.'],
            ['bagian_laporan' => 'Bab 4', 'minimum_informasi' => 'Memuat analisis dan evaluasi capaian kinerja.'],
            ['bagian_laporan' => 'Lampiran', 'minimum_informasi' => 'Memuat data dukung dan dokumen pendukung lainnya.'],
        ];

        foreach ($aspek1 as $index => $item) {
            RubrikReview::updateOrCreate(
                [
                    'aspek' => 'format_pelaporan',
                    'tipe_evaluasi' => 'per_dokumen',
                    'bagian_laporan' => $item['bagian_laporan'],
                ],
                [
                    'minimum_informasi' => $item['minimum_informasi'],
                    'urutan' => $index + 1,
                ]
            );
        }

        $aspek3 = [
            ['bagian_laporan' => 'Target vs Realisasi Tahun Ini', 'minimum_informasi' => 'Analisis perbandingan antara target dan realisasi kinerja tahun berjalan.'],
            ['bagian_laporan' => 'Realisasi Tahun Ini vs Historis', 'minimum_informasi' => 'Analisis perbandingan realisasi kinerja tahun ini dengan tahun-tahun sebelumnya.'],
            ['bagian_laporan' => 'Realisasi vs Target Renstra', 'minimum_informasi' => 'Analisis perbandingan realisasi kinerja dengan target dalam dokumen Renstra.'],
            ['bagian_laporan' => 'Realisasi vs Standar Nasional', 'minimum_informasi' => 'Analisis perbandingan realisasi kinerja dengan standar nasional yang berlaku.'],
            ['bagian_laporan' => 'Analisis Keberhasilan/Kegagalan', 'minimum_informasi' => 'Analisis faktor pendorong keberhasilan dan penghambat/kegagalan kinerja.'],
            ['bagian_laporan' => 'Analisis Efisiensi', 'minimum_informasi' => 'Analisis efisiensi penggunaan sumber daya dalam pencapaian kinerja.'],
            ['bagian_laporan' => 'Analisis Program Penunjang', 'minimum_informasi' => 'Analisis kontribusi program penunjang terhadap capaian kinerja utama.'],
        ];

        foreach ($aspek3 as $index => $item) {
            RubrikReview::updateOrCreate(
                [
                    'aspek' => 'pengungkapan_informasi',
                    'tipe_evaluasi' => 'per_indikator',
                    'bagian_laporan' => $item['bagian_laporan'],
                ],
                [
                    'minimum_informasi' => $item['minimum_informasi'],
                    'urutan' => $index + 1,
                ]
            );
        }
    }
}
