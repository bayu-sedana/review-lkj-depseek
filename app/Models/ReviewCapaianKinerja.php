<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewCapaianKinerja extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lkj_dokumen_id',
        'indikator_kinerja_id',
        'nilai_exec_summary',
        'nilai_bab_3',
        'nilai_bab_4',
        'nilai_aplikasi_kinerjaku',
        'nilai_data_dukung',
        'is_sinkron',
        'catatan_perbaikan',
        'tanggapan_perbaikan_satker',
        'direview_oleh',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nilai_exec_summary' => 'decimal:4',
            'nilai_bab_3' => 'decimal:4',
            'nilai_bab_4' => 'decimal:4',
            'nilai_aplikasi_kinerjaku' => 'decimal:4',
            'nilai_data_dukung' => 'decimal:4',
            'is_sinkron' => 'boolean',
        ];
    }

    /**
     * Get the document this review belongs to.
     */
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(LkjDokumen::class, 'lkj_dokumen_id');
    }

    /**
     * Get the indikator kinerja this review belongs to.
     */
    public function indikatorKinerja(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class);
    }

    /**
     * Get the user who performed the review.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'direview_oleh');
    }

    /**
     * Determine whether the five values are all equal.
     */
    public function hitungSinkron(): bool
    {
        $nilai = array_filter([
            $this->nilai_exec_summary,
            $this->nilai_bab_3,
            $this->nilai_bab_4,
            $this->nilai_aplikasi_kinerjaku,
            $this->nilai_data_dukung,
        ], fn ($value) => $value !== null && $value !== '');

        if (count($nilai) < 5) {
            return false;
        }

        return count(array_unique(array_map('strval', $nilai))) === 1;
    }
}
