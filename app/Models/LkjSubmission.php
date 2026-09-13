<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LkjSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'periode_id',
        'satker_id',
        'status_keseluruhan',
        'tanggal_selesai',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_selesai' => 'datetime',
        ];
    }

    /**
     * Get the period this submission belongs to.
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeReview::class, 'periode_id');
    }

    /**
     * Get the satker this submission belongs to.
     */
    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class);
    }

    /**
     * Get the documents uploaded for this submission.
     */
    public function dokumens(): HasMany
    {
        return $this->hasMany(LkjDokumen::class)->orderBy('versi');
    }

    /**
     * Get the berita acara for this submission.
     */
    public function beritaAcara(): HasOne
    {
        return $this->hasOne(BeritaAcara::class);
    }

    /**
     * Get the latest uploaded document.
     */
    public function dokumenTerakhir(): ?LkjDokumen
    {
        return $this->dokumens()->orderByDesc('versi')->first();
    }

    /**
     * Get the next version number for a new upload.
     */
    public function versiBerikutnya(): int
    {
        return ((int) $this->dokumens()->max('versi')) + 1;
    }
}
