<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaguInsentif extends Model
{
    use HasFactory;

    protected $fillable = [
        'tingkat_quartil',
        'nominal_base',
    ];

    protected $casts = [
        'nominal_base' => 'integer',
    ];
}
