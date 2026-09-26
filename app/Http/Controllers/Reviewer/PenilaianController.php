<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PenilaianController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $penugasan = $user->penelitianSebagaiReviewer()
            ->with('dosen')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reviewer.penilaian.index', compact('penugasan'));
    }

    public function evaluate(Penelitian $penelitian)
    {
        Gate::authorize('review', $penelitian);

        $penelitian->load(['dokumen', 'dosen']);
        
        return view('reviewer.penilaian.evaluate', compact('penelitian'));
    }

    public function storeDeskEvaluation(Request $request, Penelitian $penelitian)
    {
        Gate::authorize('review', $penelitian);

        $validated = $request->validate([
            'nilai_desk_eval' => 'required|numeric|min:0|max:100',
            'komentar_desk_eval' => 'required|string',
        ]);

        $penelitian->reviewer()->updateExistingPivot(Auth::id(), [
            'nilai_desk_eval' => $validated['nilai_desk_eval'],
            'komentar_desk_eval' => $validated['komentar_desk_eval'],
            'status_review' => 'reviewed_desk',
            'tanggal_dinilai' => now(),
        ]);

        return redirect()->route('reviewer.penilaian.index')->with('success', 'Penilaian Desk Evaluation berhasil disimpan.');
    }
}
