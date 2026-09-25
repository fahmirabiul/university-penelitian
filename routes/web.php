<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\SsoAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', [SsoAuthController::class, 'redirect'])->name('sso.login');
Route::get('/auth/callback', [SsoAuthController::class, 'callback']);
Route::post('/auth/logout', [SsoAuthController::class, 'logout'])->name('sso.logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dokumen/{dokumen}', [\App\Http\Controllers\DokumenController::class, 'download'])
        ->name('dokumen.download');

    // Route untuk Dosen
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::resource('penelitian', \App\Http\Controllers\Dosen\PenelitianController::class);
        Route::post('penelitian/{penelitian}/submit', [\App\Http\Controllers\Dosen\PenelitianController::class, 'submit'])->name('penelitian.submit');
    });
});

Route::get('/dokumen/signed/{dokumen}', [\App\Http\Controllers\DokumenController::class, 'downloadSigned'])
    ->name('dokumen.download.signed')
    ->middleware('signed');
