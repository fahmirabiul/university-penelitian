<?php

namespace App\Observers;

use App\Models\Penelitian;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class PenelitianObserver
{
    public function updated(Penelitian $penelitian): void
    {
        if ($penelitian->isDirty('status_saat_ini')) {
            AuditLog::create([
                'model_type'     => Penelitian::class,
                'model_id'       => $penelitian->id,
                'user_id'        => Auth::id(), // Bisa null jika dieksekusi oleh sistem/job
                'status_sebelum' => $penelitian->getOriginal('status_saat_ini'),
                'status_sesudah' => $penelitian->status_saat_ini,
                'catatan_sistem' => 'Status penelitian diubah.',
            ]);
        }
    }
}
