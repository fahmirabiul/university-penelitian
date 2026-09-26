<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['sso_id', 'name', 'email', 'role_lokal'])]
#[Hidden(['remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // password dan email_verified_at dihapus karena via SSO
        ];
    }

    public function penelitian()
    {
        return $this->belongsToMany(Penelitian::class, 'penelitian_dosen')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function penelitianSebagaiReviewer()
    {
        return $this->belongsToMany(Penelitian::class, 'penelitian_reviewer')
            ->withPivot(['nilai_desk_eval', 'komentar_desk_eval', 'nilai_presentasi', 'komentar_presentasi', 'status_review', 'tanggal_dinilai'])
            ->withTimestamps();
    }
}
