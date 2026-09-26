@extends('layouts.app')

@section('title', 'Form Evaluasi')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Reviewer / Penugasan /</span> Evaluasi Proposal
</h4>

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <!-- Kiri: Detail Proposal -->
    <div class="col-md-7">
        <div class="card mb-4">
            <h5 class="card-header">Detail Proposal</h5>
            <div class="card-body">
                <h5 class="fw-bold text-primary">{{ $penelitian->judul }}</h5>
                <p class="text-muted mb-4">{{ $penelitian->skema_penelitian }} | Tahun Akademik: {{ $penelitian->tahun_akademik }}</p>
                
                <h6 class="fw-bold">Abstrak</h6>
                <p class="text-muted text-justify">{{ $penelitian->abstrak }}</p>

                <h6 class="mt-4 fw-bold">Dokumen Pendukung</h6>
                @if($penelitian->dokumen->count() > 0)
                    <div class="d-flex flex-column gap-2 mt-2">
                        @foreach($penelitian->dokumen as $dok)
                            <a href="{{ route('dokumen.download', $dok->id) }}" class="btn btn-outline-secondary text-start" target="_blank">
                                <i class="bx bxs-file-pdf text-danger me-2"></i> {{ $dok->nama_file }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-danger">Tidak ada dokumen yang dilampirkan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Kanan: Form Evaluasi -->
    <div class="col-md-5">
        <div class="card mb-4">
            <h5 class="card-header border-bottom">Rubrik Penilaian (Desk Evaluation)</h5>
            <div class="card-body pt-4">
                @php
                    // Get pivot explicitly from the currently logged in user's evaluation
                    $pivot = $penelitian->reviewer->where('id', Auth::id())->first()->pivot ?? null;
                    $isReviewed = $pivot ? in_array($pivot->status_review, ['reviewed_desk', 'reviewed_presentasi']) : false;
                @endphp

                <form action="{{ route('reviewer.penilaian.storeDesk', $penelitian->id) }}" method="POST" id="formEvaluasi">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="nilai_desk_eval">Nilai (0 - 100) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="nilai_desk_eval" name="nilai_desk_eval" min="0" max="100" 
                               value="{{ old('nilai_desk_eval', $pivot->nilai_desk_eval ?? '') }}" 
                               {{ $isReviewed ? 'disabled' : 'required' }}>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="komentar_desk_eval">Catatan / Komentar Perbaikan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="komentar_desk_eval" name="komentar_desk_eval" rows="5" 
                                  placeholder="Berikan alasan atau catatan perbaikan yang konstruktif..."
                                  {{ $isReviewed ? 'disabled' : 'required' }}>{{ old('komentar_desk_eval', $pivot->komentar_desk_eval ?? '') }}</textarea>
                    </div>

                    @if(!$isReviewed)
                        <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Apakah Anda yakin ingin mengirim nilai? Data tidak dapat diubah setelah di-submit.')">
                            <i class="bx bx-send me-1"></i> Submit Penilaian
                        </button>
                    @else
                        <div class="alert alert-success">
                            <i class="bx bx-check-circle me-1"></i> Anda sudah memberikan penilaian untuk tahap ini.
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
