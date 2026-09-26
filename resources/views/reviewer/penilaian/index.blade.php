@extends('layouts.app')

@section('title', 'Penugasan Review')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Reviewer /</span> Daftar Penugasan
</h4>

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Proposal yang Perlu Dinilai</h5>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Judul Proposal</th>
                    <th>Ketua Peneliti</th>
                    <th>Skema</th>
                    <th>Status Anda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($penugasan as $penelitian)
                <tr>
                    <td><strong>{{ Str::limit($penelitian->judul, 40) }}</strong></td>
                    <td>
                        {{ $penelitian->dosen->where('pivot.peran', 'ketua')->first()->name ?? 'N/A' }}
                    </td>
                    <td>{{ $penelitian->skema_penelitian }}</td>
                    <td>
                        @php
                            $status = $penelitian->pivot->status_review;
                            $badgeColor = match($status) {
                                'pending' => 'bg-label-warning',
                                'reviewed_desk' => 'bg-label-success',
                                'reviewed_presentasi' => 'bg-label-primary',
                                default => 'bg-label-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeColor }}">
                            {{ Str::upper(str_replace('_', ' ', $status)) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('reviewer.penilaian.evaluate', $penelitian->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-edit-alt"></i> Evaluasi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Tidak ada penugasan evaluasi saat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
