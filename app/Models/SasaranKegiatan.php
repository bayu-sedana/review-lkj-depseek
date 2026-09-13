<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SasaranKegiatan extends Model
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
        'sasaran_kegiatan',
    ];

    /**
     * Get the period this sasaran belongs to.
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeReview::class, 'periode_id');
    }

    /**
     * Get the satker this sasaran belongs to.
     */
    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class);
    }

    /**
     * Get the indikator kinerja under this sasaran.
     */
    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class);
    }
}
