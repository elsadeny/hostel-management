<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\RoomChangeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student Portal Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/receipt/{allocation}', [DashboardController::class, 'downloadReceipt'])->name('receipt.download');
    Route::post('/dashboard/receipt/{allocation}/generate', [DashboardController::class, 'generateReceipt'])->name('receipt.generate');
    Route::get('/room-change', [RoomChangeController::class, 'index'])->name('room-change');
    Route::post('/room-change', [RoomChangeController::class, 'store'])->name('room-change.store');
});

require __DIR__ . '/auth.php';
