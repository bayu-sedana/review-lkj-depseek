<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndikatorKinerja extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sasaran_kegiatan_id',
        'indikator_kinerja',
    ];

    /**
     * Get the sasaran kegiatan this indikator belongs to.
     */
    public function sasaranKegiatan(): BelongsTo
    {
        return $this->belongsTo(SasaranKegiatan::class);
    }
}
