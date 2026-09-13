<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanMonev extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penugasan_monev';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'periode_id',
        'satker_id',
        'monev_user_id',
    ];

    /**
     * Get the period this assignment belongs to.
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeReview::class, 'periode_id');
    }

    /**
     * Get the satker this assignment belongs to.
     */
    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class);
    }

    /**
     * Get the monev user assigned to this satker.
     */
    public function monevUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monev_user_id');
    }
}
