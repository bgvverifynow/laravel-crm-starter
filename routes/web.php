<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/create-admin', function () {

    \App\Models\User::updateOrCreate(
        ['email' => 'admin@verifynow.in'],
        [
            'name' => 'Admin',
            'password' => bcrypt('Admin@123'),
        ]
    );

    return 'Admin Created';
});

require __DIR__.'/auth.php';
