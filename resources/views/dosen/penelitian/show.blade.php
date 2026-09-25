@extends('layouts.app')

@section('title', 'Detail Usulan Penelitian')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Dosen / Usulan Penelitian /</span> Detail
</h4>

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <!-- Detail Proposal -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-1 order-md-0">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="pb-2 border-bottom mb-4">Informasi Utama</h5>
                <div class="info-container">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <span class="fw-medium me-2">Judul:</span>
                            <span>{{ $penelitian->judul }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-medium me-2">Status Saat Ini:</span>
                            <span class="badge bg-label-primary">{{ Str::upper($penelitian->status_saat_ini) }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-medium me-2">Skema:</span>
                            <span>{{ $penelitian->skema_penelitian }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-medium me-2">Tahun Akademik:</span>
                            <span>{{ $penelitian->tahun_akademik }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-medium me-2">Total Dana Diajukan:</span>
                            <span>Rp {{ number_format($penelitian->total_dana_diajukan, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
                
                <h5 class="pb-2 border-bottom mt-5 mb-4">Abstrak</h5>
                <p>{{ $penelitian->abstrak }}</p>

                <h5 class="pb-2 border-bottom mt-5 mb-4">Dokumen Pendukung</h5>
                @if($penelitian->dokumen->count() > 0)
                    <ul class="list-group">
                        @foreach($penelitian->dokumen as $dok)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $dok->nama_dokumen }}
                                <a href="{{ route('dokumen.download', $dok->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">Unduh</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada dokumen.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Aksi & History -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-0 order-md-1">
        <!-- Aksi -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Aksi Proposal</h5>
                @if($penelitian->status_saat_ini === 'draft')
                    <form action="{{ route('dosen.penelitian.submit', $penelitian->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 mb-3" onclick="return confirm('Apakah Anda yakin ingin mensubmit proposal ini? Setelah disubmit, proposal tidak dapat diubah.')">
                            Ajukan Proposal
                        </button>
                    </form>
                @else
                    <div class="alert alert-info">
                        Proposal telah diajukan dan sedang diproses. Anda tidak dapat mengubah data proposal.
                    </div>
                @endif
                <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-outline-secondary w-100">Kembali</a>
            </div>
        </div>

        <!-- History/Audit Logs -->
        <div class="card mb-4">
            <h5 class="card-header">Riwayat Status</h5>
            <div class="card-body">
                <ul class="timeline">
                    @forelse($auditLogs as $log)
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point-wrapper">
                            <span class="timeline-point timeline-point-primary"></span>
                        </span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">{{ Str::upper($log->status_sesudah) }}</h6>
                                <small class="text-muted">{{ $log->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <p class="mb-2">{{ $log->catatan_sistem }}</p>
                        </div>
                    </li>
                    @empty
                    <li class="timeline-item timeline-item-transparent">
                        <div class="timeline-event">
                            <p class="mb-0 text-muted">Belum ada riwayat perubahan status.</p>
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
