@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->name }}!</h5>
                <p class="mb-4">
                    Anda login sebagai <strong>{{ Str::title(str_replace('_', ' ', auth()->user()->role_lokal ?? 'Guest')) }}</strong>.
                </p>
                <p>
                    Silakan gunakan menu di navigasi sebelah kiri untuk mengakses fitur aplikasi sesuai dengan peran Anda.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
