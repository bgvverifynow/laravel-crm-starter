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

/*
|--------------------------------------------------------------------------
| Create Admin User
|--------------------------------------------------------------------------
*/

Route::get('/create-admin-verify', function () {

    $user = \App\Models\User::updateOrCreate(
        ['email' => 'admin@verifynow.in'],
        [
            'name' => 'Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('Admin@123'),
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Admin Created'
    ]);

});

/*
|--------------------------------------------------------------------------
| Make Super Admin
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Fix Permissions
|--------------------------------------------------------------------------
*/

Route::get('/fix-permissions', function () {

    $user = \App\Models\User::where('email', 'admin@verifynow.in')->first();

    if (!$user) {
        return 'User not found';
    }

    $permissions = \Spatie\Permission\Models\Permission::all();

    foreach ($permissions as $permission) {
        $user->givePermissionTo($permission);
    }

    return 'Permissions Fixed';

});

require __DIR__.'/auth.php';
