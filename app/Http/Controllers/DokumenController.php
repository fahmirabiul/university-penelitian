<?php

namespace App\Http\Controllers;

use App\Models\DokumenPenelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DokumenController extends Controller
{
    public function download(DokumenPenelitian $dokumen): StreamedResponse
    {
        // Otentikasi & Otorisasi akses berdasarkan relasi penelitian
        Gate::authorize('view', $dokumen->documentable);

        if (!Storage::disk('local')->exists($dokumen->file_path)) {
            abort(404, 'Dokumen tidak ditemukan di penyimpanan server.');
        }

        return Storage::disk('local')->download($dokumen->file_path);
    }

    public function downloadSigned(Request $request, DokumenPenelitian $dokumen): StreamedResponse
    {
        // Middleware 'signed' pada rute sudah memastikan validitas URL ini
        if (!Storage::disk('local')->exists($dokumen->file_path)) {
            abort(404, 'Dokumen tidak ditemukan di penyimpanan server.');
        }

        return Storage::disk('local')->download($dokumen->file_path);
    }
}
