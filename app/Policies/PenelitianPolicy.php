<?php

namespace App\Policies;

use App\Models\Penelitian;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PenelitianPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Penelitian $penelitian): Response
    {
        $isAuthor = $penelitian->dosen()->where('user_id', $user->id)->exists();
        $isReviewer = $penelitian->reviewer()->where('user_id', $user->id)->exists();

        if ($isAuthor || $isReviewer || $user->role_lokal === 'admin_lembaga') {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki izin untuk melihat data penelitian ini.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Penelitian $penelitian): Response
    {
        $isAuthor = $penelitian->dosen()->where('user_id', $user->id)->exists();
        $isEditableState = in_array($penelitian->status_saat_ini, ['draft', 'revisi']);

        if (!$isAuthor) {
            return Response::deny('Anda bukan anggota dari penelitian ini.');
        }

        if (!$isEditableState) {
            return Response::deny('Penelitian tidak dapat diubah karena statusnya saat ini adalah: ' . $penelitian->status_saat_ini);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can review the model.
     */
    public function review(User $user, Penelitian $penelitian): Response
    {
        $isAuthor = $penelitian->dosen()->where('user_id', $user->id)->exists();

        if ($isAuthor) {
            return Response::deny('Anda tidak diizinkan untuk meninjau proposal Anda sendiri (Conflict of Interest).');
        }

        $isAssignedReviewer = $penelitian->reviewer()->where('user_id', $user->id)->exists();
        
        if (!$isAssignedReviewer) {
            return Response::deny('Anda belum ditugaskan sebagai reviewer untuk penelitian ini.');
        }

        return Response::allow();
    }
}
