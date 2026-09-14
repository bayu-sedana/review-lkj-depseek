<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerwakilanSatker extends Model
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
        'user_id',
        'urutan',
    ];

    /**
     * Get the period this representative belongs to.
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeReview::class, 'periode_id');
    }

    /**
     * Get the satker this representative belongs to.
     */
    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class);
    }

    /**
     * Get the user acting as representative.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
