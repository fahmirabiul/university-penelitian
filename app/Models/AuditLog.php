<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    use HasFactory;
    
    const UPDATED_AT = null;

    protected $fillable = [
        'model_type',
        'model_id',
        'user_id',
        'status_sebelum',
        'status_sesudah',
        'catatan_sistem',
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}
