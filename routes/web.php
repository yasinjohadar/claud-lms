<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServerTestController;
use App\Support\AuthRedirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return redirect(AuthRedirect::intendedFor(Auth::user()));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'check.user.active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/student.php';
require __DIR__.'/frontend.php';

Route::get('/server-test', [ServerTestController::class, 'index'])->name('server.test');
