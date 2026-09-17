<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        | Dashboard
        */
        Route::get('/dashboard', [
            AdminController::class,
            'dashboard'
        ])->name('dashboard');

        /*
        | User Management
        */
        Route::get('/users', [
            AdminController::class,
            'users'
        ])->name('users');

        /*
        | Update Role
        */
        Route::patch('/users/{user}/role', [
            AdminController::class,
            'updateRole'
        ])->name('users.update-role');

        /*
        | Activate / Deactivate
        */
        Route::patch('/users/{user}/activate', [
            AdminController::class,
            'activate'
        ])->name('users.activate');

        Route::patch('/users/{user}/deactivate', [
            AdminController::class,
            'deactivate'
        ])->name('users.deactivate');

        /*
        | Bulk Actions
        */
        Route::post('/users/bulk-activate', [
            AdminController::class,
            'bulkActivate'
        ])->name('users.bulk-activate');

        Route::post('/users/bulk-deactivate', [
            AdminController::class,
            'bulkDeactivate'
        ])->name('users.bulk-deactivate');

        Route::post('/users/bulk-role', [
            AdminController::class,
            'bulkRole'
        ])->name('users.bulk-role');

        /*
        | CSV Export
        */
        Route::get('/users/export', [
            AdminController::class,
            'exportUsers'
        ])->name('users.export');
    });

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', [
            CustomerController::class,
            'dashboard'
        ])->name('dashboard');
    });

require __DIR__ . '/auth.php';