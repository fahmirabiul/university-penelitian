<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insentif extends Model
{
    use HasFactory;

    protected $fillable = [
        'publikasi_id',
        'periode_insentif_id',
        'status',
        'total_dana',
    ];

    protected $casts = [
        'total_dana' => 'integer',
    ];

    public function publikasi(): BelongsTo
    {
        return $this->belongsTo(Publikasi::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeInsentif::class, 'periode_insentif_id');
    }

    public function distribusi(): HasMany
    {
        return $this->hasMany(InsentifDistribusi::class);
    }
}
