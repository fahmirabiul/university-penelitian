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

Route::get('/dashboard', function (Request $request) {
    return 'Welcome ' . $request->user()->name;
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dokumen/{dokumen}', [\App\Http\Controllers\DokumenController::class, 'download'])
        ->name('dokumen.download');
});

Route::get('/dokumen/signed/{dokumen}', [\App\Http\Controllers\DokumenController::class, 'downloadSigned'])
    ->name('dokumen.download.signed')
    ->middleware('signed');
