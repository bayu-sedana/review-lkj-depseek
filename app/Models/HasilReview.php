<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lkj_dokumen_id',
        'rubrik_id',
        'indikator_kinerja_id',
        'uraian_hasil_review',
        'status',
        'catatan_perbaikan',
        'tanggapan_perbaikan_satker',
        'direview_oleh',
    ];

    /**
     * Get the document this review belongs to.
     */
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(LkjDokumen::class, 'lkj_dokumen_id');
    }

    /**
     * Get the rubric used for this review.
     */
    public function rubrik(): BelongsTo
    {
        return $this->belongsTo(RubrikReview::class, 'rubrik_id');
    }

    /**
     * Get the indikator kinerja this review belongs to (Aspek 3 only).
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
     * Determine whether this review is marked as compliant.
     */
    public function isSesuai(): bool
    {
        return $this->status === 'Sesuai';
    }
}
