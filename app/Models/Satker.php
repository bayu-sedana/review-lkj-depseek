<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_satker',
        'nama_satker',
    ];

    /**
     * Get the users that belong to this satker.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the monev assignments for this satker.
     */
    public function penugasanMonev(): HasMany
    {
        return $this->hasMany(PenugasanMonev::class);
    }

    /**
     * Get the sasaran kegiatans for this satker.
     */
    public function sasaranKegiatan(): HasMany
    {
        return $this->hasMany(SasaranKegiatan::class);
    }

    /**
     * Get the LKj submissions for this satker.
     */
    public function lkjSubmissions(): HasMany
    {
        return $this->hasMany(LkjSubmission::class);
    }
}
