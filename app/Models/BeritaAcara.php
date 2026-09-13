<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcara extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lkj_submission_id',
        'file_word_path',
        'file_pdf_path',
        'diupload_oleh',
    ];

    /**
     * Get the submission this berita acara belongs to.
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(LkjSubmission::class, 'lkj_submission_id');
    }

    /**
     * Get the user who uploaded the scanned PDF.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diupload_oleh');
    }

    /**
     * Determine whether the generated Word file exists.
     */
    public function hasWord(): bool
    {
        return filled($this->file_word_path);
    }

    /**
     * Determine whether the scanned PDF has been uploaded.
     */
    public function hasPdf(): bool
    {
        return filled($this->file_pdf_path);
    }
}
