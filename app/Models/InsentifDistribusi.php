<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsentifDistribusi extends Model
{
    use HasFactory;

    protected $fillable = [
        'insentif_id',
        'user_id',
        'peran',
        'persentase_potongan',
        'nominal_final',
    ];

    protected $casts = [
        'persentase_potongan' => 'integer',
        'nominal_final' => 'integer',
    ];

    public function insentif(): BelongsTo
    {
        return $this->belongsTo(Insentif::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
