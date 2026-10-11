<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PjController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;


Route::redirect('/', '/login');

Schedule::command('siperu:sync-room-status')
    ->everyMinute()
    ->withoutOverlapping();
    
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

  
    // PJ Ruangan/Laboratorium
    Route::middleware('role:pj_ruangan')->prefix('pj')->name('pj.')->group(function () {
    Route::get('/pengajuan', [PjController::class, 'requests'])->name('requests.index'); // Pengajuan masuk
    Route::get('/pengajuan/{booking}', [PjController::class, 'show'])->name('requests.show'); // Detail pengajuan
    Route::post('/pengajuan/{booking}/setujui', [PjController::class, 'approve'])->name('requests.approve'); // Setujui
    Route::post('/pengajuan/{booking}/tolak', [PjController::class, 'reject'])->name('requests.reject'); // Tolak
    Route::get('/jadwal', [PjController::class, 'schedule'])->name('schedule'); // Jadwal
    Route::get('/riwayat-verifikasi', [PjController::class, 'history'])->name('history'); // Riwayat verifikasi
    });

    // role:admin      -> kelola data, laporan
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/pengguna', [AdminController::class, 'users'])->name('users.index');
    Route::get('/pengguna/tambah', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/pengguna', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/pengguna/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/pengguna/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::patch('/pengguna/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');

    Route::get('/ruangan', [AdminController::class, 'rooms'])->name('rooms.index');
    Route::get('/ruangan/tambah', [AdminController::class, 'createRoom'])->name('rooms.create');
    Route::post('/ruangan', [AdminController::class, 'storeRoom'])->name('rooms.store');
    Route::get('/ruangan/{room}/edit', [AdminController::class, 'editRoom'])->name('rooms.edit');
    Route::put('/ruangan/{room}', [AdminController::class, 'updateRoom'])->name('rooms.update');
    Route::patch('/ruangan/{room}/toggle', [AdminController::class, 'toggleRoom'])->name('rooms.toggle');
    Route::delete('/ruangan/{room}', [AdminController::class, 'destroyRoom'])->name('rooms.destroy');
    Route::patch('/ruangan/{room}/restore', [AdminController::class, 'restoreRoom'])->name('rooms.restore');    
});
});

require __DIR__ . '/auth.php';
