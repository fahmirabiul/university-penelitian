@extends('layouts.app')

@section('title', 'Detail Proposal (Admin)')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Admin / Penelitian /</span> Detail Proposal
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
    <div class="col-md-8">
        <div class="card mb-4">
            <h5 class="card-header">Informasi Proposal</h5>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Judul</dt>
                    <dd class="col-sm-9">{{ $penelitian->judul }}</dd>

                    <dt class="col-sm-3">Tahun Akademik</dt>
                    <dd class="col-sm-9">{{ $penelitian->tahun_akademik }}</dd>

                    <dt class="col-sm-3">Skema</dt>
                    <dd class="col-sm-9">{{ $penelitian->skema_penelitian }}</dd>
                    
                    <dt class="col-sm-3">Dana Diajukan</dt>
                    <dd class="col-sm-9">Rp {{ number_format($penelitian->dana_diajukan, 0, ',', '.') }}</dd>
                    
                    <dt class="col-sm-3">Status Saat Ini</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-label-info">{{ Str::upper($penelitian->status_saat_ini) }}</span>
                    </dd>
                </dl>
                
                <h6 class="mt-4 fw-bold">Abstrak</h6>
                <p class="text-muted">{{ $penelitian->abstrak }}</p>

                <h6 class="mt-4 fw-bold">Dokumen Pendukung</h6>
                @if($penelitian->dokumen->count() > 0)
                    <ul class="list-group">
                        @foreach($penelitian->dokumen as $dok)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $dok->nama_file }}
                                <a href="{{ route('dokumen.download', $dok->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="bx bx-download"></i> Unduh
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-danger">Tidak ada dokumen yang dilampirkan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Kanan: Panel Penugasan Reviewer -->
    <div class="col-md-4">
        <div class="card mb-4">
            <h5 class="card-header border-bottom">Penugasan Reviewer</h5>
            <div class="card-body pt-4">
                @if($penelitian->status_saat_ini === 'submitted')
                    <form action="{{ route('admin.penelitian.assignReviewer', $penelitian->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="reviewer_ids">Pilih Dosen (Maksimal 2)</label>
                            <select name="reviewer_ids[]" id="reviewer_ids" class="form-select select2" multiple required>
                                @foreach($calonReviewer as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih dosen penilai untuk tahap Desk Evaluation.</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Tugaskan Reviewer</button>
                    </form>
                @else
                    <div class="alert alert-secondary">
                        Penugasan reviewer sudah dilakukan atau tidak tersedia di status saat ini.
                    </div>
                @endif

                @if($penelitian->reviewer->count() > 0)
                    <h6 class="mt-4 fw-bold">Daftar Reviewer Terpilih:</h6>
                    <ul class="list-group list-group-flush">
                        @foreach($penelitian->reviewer as $rev)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="bx bx-user me-1"></i> {{ $rev->name }}
                                </div>
                                <span class="badge bg-label-{{ $rev->pivot->status_review == 'pending' ? 'warning' : 'success' }}">
                                    {{ $rev->pivot->status_review }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        @if($penelitian->status_saat_ini === 'desk_eval')
        <div class="card mb-4">
            <h5 class="card-header border-bottom">Keputusan Akhir</h5>
            <div class="card-body pt-4">
                @if(!$penelitian->isAllReviewersFinished())
                    <div class="alert alert-warning">
                        <i class="bx bx-time-five me-1"></i> Menunggu semua reviewer selesai menilai.
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="bx bx-check-circle me-1"></i> Semua reviewer telah selesai.
                    </div>
                    
                    @php
                        $avg = $penelitian->reviewer->avg('pivot.nilai_desk_eval');
                    @endphp
                    <div class="mb-3 text-center">
                        <span class="d-block text-muted mb-1">Rata-rata Nilai</span>
                        <h3 class="text-primary mb-0">{{ number_format($avg, 2) }}</h3>
                    </div>

                    <form action="{{ route('admin.penelitian.decide', $penelitian->id) }}" method="POST">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" name="keputusan" value="approved" class="btn btn-success" onclick="return confirm('Terima proposal ini?')">
                                <i class="bx bx-check me-1"></i> Terima (Approve)
                            </button>
                            <button type="submit" name="keputusan" value="rejected" class="btn btn-outline-danger" onclick="return confirm('Tolak proposal ini?')">
                                <i class="bx bx-x me-1"></i> Tolak (Reject)
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        border-color: #d9dee3;
        min-height: calc(1.5em + 1.09rem + 2px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#reviewer_ids').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih maksimal 2 dosen",
            allowClear: true,
            maximumSelectionLength: 2
        });
    });
</script>
@endpush
