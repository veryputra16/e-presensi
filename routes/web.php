<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\PresenceDetailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

// Admin
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/chart-data', [App\Http\Controllers\HomeController::class, 'chartData']);
    Route::resource('presence', PresenceController::class);

    // QR Code untuk absensi
    Route::get('presence/{id}/qrcode', [PresenceController::class, 'showQrCode'])->name('presence.qrcode.view');
    Route::get('presence/{id}/qrcode/download', [PresenceController::class, 'downloadQrCode'])->name('presence.qrcode.download');

    Route::delete('presence-detail/{id}', [PresenceDetailController::class, 'destroy'])->name('presence-detail.destroy');
    Route::get('presence-detail/export-pdf/{id}', [PresenceDetailController::class, 'exportPdf'])->name('presence-detail.export-pdf');
});

// Publik
Route::get('absen/{slug}', [AbsenController::class, 'index'])->name('absen.index');
Route::post('absen/save/{id}', [AbsenController::class, 'save'])->name('absen.save');

// halaman QR scan ke form mandiri
Route::get('qr-form/{slug}', [AbsenController::class, 'formManual'])->name('absen.qrmanual');
Route::post('absen/save/{id}', [AbsenController::class, 'save'])->name('absen.save');

// Auth
Auth::routes([
    'register' => false, // Ganti register jadi true jika ingin mengaktifkan fitur register
    'reset' => false
]);
