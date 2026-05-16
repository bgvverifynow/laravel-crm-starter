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

Route::get('/make-super-admin', function () {

    $user = \App\Models\User::where('email', 'admin@verifynow.in')->first();

    if (!$user) {
        return 'User not found';
    }

    // Create role if missing
    $role = \Spatie\Permission\Models\Role::firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web'
    ]);

    // Assign role
    $user->assignRole($role);

    return 'Super Admin Assigned';

});

require __DIR__.'/auth.php';
