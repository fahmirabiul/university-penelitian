@extends('layouts.app')

@section('title', 'Usulan Penelitian')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Dosen /</span> Usulan Penelitian
</h4>

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Usulan Penelitian Saya</h5>
        <a href="{{ route('dosen.penelitian.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Buat Usulan Baru
        </a>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tahun</th>
                    <th>Skema</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($penelitians as $penelitian)
                <tr>
                    <td><strong>{{ Str::limit($penelitian->judul, 40) }}</strong></td>
                    <td>{{ $penelitian->tahun_akademik }}</td>
                    <td>{{ $penelitian->skema_penelitian }}</td>
                    <td>
                        @php
                            $badgeColor = match($penelitian->status_saat_ini) {
                                'draft' => 'bg-label-secondary',
                                'submitted' => 'bg-label-info',
                                'desk_evaluation' => 'bg-label-warning',
                                'approved' => 'bg-label-success',
                                'rejected' => 'bg-label-danger',
                                default => 'bg-label-primary'
                            };
                        @endphp
                        <span class="badge {{ $badgeColor }}">{{ Str::upper($penelitian->status_saat_ini) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('dosen.penelitian.show', $penelitian->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-show-alt"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada usulan penelitian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
