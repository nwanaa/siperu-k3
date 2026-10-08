<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifikasi (semua role)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Profil (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Surat izin: otorisasi (pemilik / PJ ruangan / admin) dicek di controller
    Route::get('/bookings/{booking}/surat', [BookingController::class, 'letter'])->name('bookings.letter');

    // Peminjam (mahasiswa & dosen)
    Route::middleware('role:peminjam')->group(function () {
        Route::get('/kalender', [CalendarController::class, 'index'])->name('kalender');

        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/riwayat', [BookingController::class, 'history'])->name('bookings.history');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/batal', [BookingController::class, 'cancel'])->name('bookings.cancel');
    });

    // TODO tahap berikutnya:
    // role:pj_ruangan -> pengajuan masuk, detail, setuju/tolak, jadwal, riwayat verifikasi
    // role:admin      -> kelola data, laporan
});

require __DIR__ . '/auth.php';
