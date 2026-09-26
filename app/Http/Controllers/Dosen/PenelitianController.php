<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PenelitianController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $penelitians = $user->penelitian()->orderBy('created_at', 'desc')->get();
        return view('dosen.penelitian.index', compact('penelitians'));
    }

    public function create()
    {
        return view('dosen.penelitian.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|string',
            'tahun_akademik' => 'required|string|max:9',
            'skema_penelitian' => 'required|string',
            'total_dana_diajukan' => 'required|numeric',
            'proposal_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        $penelitian = Penelitian::create([
            'judul' => $validated['judul'],
            'abstrak' => $validated['abstrak'],
            'tahun_akademik' => $validated['tahun_akademik'],
            'skema_penelitian' => $validated['skema_penelitian'],
            'total_dana_diajukan' => $validated['total_dana_diajukan'],
            'status_saat_ini' => 'draft',
            'tanggal_pengajuan' => now(),
        ]);

        $penelitian->dosen()->attach(Auth::id(), ['peran' => 'Ketua']);

        if ($request->hasFile('proposal_file')) {
            $path = $request->file('proposal_file')->store('private/dokumen_proposal');
            $penelitian->dokumen()->create([
                'tipe_dokumen' => 'proposal',
                'file_path' => $path,
                'uploaded_by' => Auth::id(),
            ]);
        }

        return redirect()->route('dosen.penelitian.index')->with('success', 'Usulan penelitian berhasil disimpan.');
    }

    public function show(Penelitian $penelitian)
    {
        Gate::authorize('view', $penelitian);

        $penelitian->load(['dokumen', 'dosen', 'reviewer']);

        $auditLogs = \App\Models\AuditLog::where('model_type', Penelitian::class)
            ->where('model_id', $penelitian->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dosen.penelitian.show', compact('penelitian', 'auditLogs'));
    }

    public function submit(Penelitian $penelitian)
    {
        Gate::authorize('update', $penelitian);

        if ($penelitian->status_saat_ini !== 'draft') {
            return back()->with('error', 'Hanya proposal berstatus draft yang dapat disubmit.');
        }

        $penelitian->update(['status_saat_ini' => 'submitted']);

        return back()->with('success', 'Proposal berhasil diajukan.');
    }
}
