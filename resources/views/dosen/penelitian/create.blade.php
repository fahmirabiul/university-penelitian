@extends('layouts.app')

@section('title', 'Buat Usulan Penelitian')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Dosen / Usulan Penelitian /</span> Buat Baru
</h4>

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Formulir Pengajuan Proposal</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dosen.penelitian.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="judul">Judul Penelitian</label>
                        <input type="text" class="form-control" id="judul" name="judul" required />
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="abstrak">Abstrak</label>
                        <textarea class="form-control" id="abstrak" name="abstrak" rows="4" required></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="tahun_akademik">Tahun Akademik</label>
                            <input type="text" class="form-control" id="tahun_akademik" name="tahun_akademik" placeholder="2023/2024" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="skema_penelitian">Skema Penelitian</label>
                            <select id="skema_penelitian" class="form-select" name="skema_penelitian" required>
                                <option value="">Pilih Skema</option>
                                <option value="Penelitian Dasar">Penelitian Dasar</option>
                                <option value="Penelitian Terapan">Penelitian Terapan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="total_dana_diajukan">Total Dana (Rp)</label>
                        <input type="number" class="form-control" id="total_dana_diajukan" name="total_dana_diajukan" required />
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="proposal_file">Dokumen Proposal (PDF)</label>
                        <input class="form-control" type="file" id="proposal_file" name="proposal_file" accept=".pdf" required />
                        <div class="form-text">File proposal maksimal 5MB.</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Simpan Draft</button>
                    <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
