<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\HasResearchState;

class Penelitian extends Model
{
    use HasFactory, HasResearchState;

    protected $fillable = [
        'judul',
        'abstrak',
        'status_saat_ini',
        'tanggal_pengajuan',
        'skema_penelitian',
        'total_dana_diajukan',
        'tahun_akademik',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'total_dana_diajukan' => 'integer',
    ];

    public function dosen(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'penelitian_dosen')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(Mahasiswa::class, 'penelitian_mahasiswa')
            ->withTimestamps();
    }

    public function reviewer(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'penelitian_reviewer')
            ->withPivot([
                'nilai_desk_eval', 
                'komentar_desk_eval', 
                'nilai_presentasi', 
                'komentar_presentasi', 
                'status_review', 
                'tanggal_dinilai'
            ])
            ->withTimestamps();
    }

    public function dokumen(): MorphMany
    {
        return $this->morphMany(DokumenPenelitian::class, 'documentable');
    }
    public function isAllReviewersFinished(): bool
    {
        if ($this->reviewer()->count() === 0) {
            return false;
        }
        return $this->reviewer()->wherePivot('status_review', 'pending')->count() === 0;
    }
}

