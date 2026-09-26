<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PenelitianController extends Controller
{
    public function index()
    {
        $penelitians = Penelitian::with('dosen')->orderBy('created_at', 'desc')->get();
        return view('admin.penelitian.index', compact('penelitians'));
    }

    public function show(Penelitian $penelitian)
    {
        $penelitian->load(['dokumen', 'dosen', 'reviewer']);

        $calonReviewer = User::where('role_lokal', 'dosen')
            ->whereNotIn('id', $penelitian->dosen->pluck('id'))
            ->get();

        return view('admin.penelitian.show', compact('penelitian', 'calonReviewer'));
    }

    public function assignReviewer(Request $request, Penelitian $penelitian)
    {
        $validated = $request->validate([
            'reviewer_ids' => 'required|array|min:1|max:2',
            'reviewer_ids.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($penelitian, $validated) {
            foreach ($validated['reviewer_ids'] as $reviewerId) {
                $penelitian->reviewer()->syncWithoutDetaching([
                    $reviewerId => ['status_review' => 'pending']
                ]);
            }

            if ($penelitian->status_saat_ini === 'submitted') {
                $penelitian->update(['status_saat_ini' => 'desk_eval']);
            }
        });

        return back()->with('success', 'Reviewer berhasil ditugaskan dan status diperbarui.');
    }
    public function decide(Request $request, Penelitian $penelitian)
    {
        Gate::authorize('decide', $penelitian);

        $validated = $request->validate([
            'keputusan' => 'required|in:approved,rejected',
        ]);

        if ($penelitian->status_saat_ini !== 'desk_eval' || !$penelitian->isAllReviewersFinished()) {
            return back()->with('error', 'Proposal belum memenuhi syarat untuk diputuskan.');
        }

        try {
            if ($validated['keputusan'] === 'approved') {
                $penelitian->state()->approve();
            } else {
                $penelitian->state()->reject();
            }
            return back()->with('success', 'Keputusan akhir berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses keputusan: ' . $e->getMessage());
        }
    }
}
