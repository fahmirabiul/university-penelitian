<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'penelitian_id',
        'judul_publikasi',
        'tingkat_quartil',
        'informasi_jurnal',
        'status_publikasi',
    ];

    protected $casts = [
        'informasi_jurnal' => 'array',
    ];

    public function penelitian(): BelongsTo
    {
        return $this->belongsTo(Penelitian::class);
    }
}
