<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tahun_lkj',
        'tahun_review',
        'deadline_revisi',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tahun_lkj' => 'integer',
            'tahun_review' => 'integer',
            'deadline_revisi' => 'date',
        ];
    }

    /**
     * Get the monev assignments for this period.
     */
    public function penugasanMonev(): HasMany
    {
        return $this->hasMany(PenugasanMonev::class, 'periode_id');
    }

    /**
     * Determine whether this period is currently active.
     */
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
