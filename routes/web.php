<?php

use App\Http\Controllers\DataImportController;
use App\Http\Controllers\DataViewController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.home');
    }

    return view('auth.login');
});

Auth::routes();

Route::get('/admin', [HomeController::class, 'index'])->name('admin.home')->middleware('auth');

Route::middleware(['auth', 'can:user-management'])->prefix('admin')->group(function() {
    Route::resource('users', UserController::class);
    Route::get('users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::post('users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.updatePermissions');
});

Route::middleware(['auth', 'can:user-management'])->group(function () {
    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permission}/assign', [PermissionController::class, 'assign'])->name('permissions.assign');
    Route::post('permissions/{permission}/assign', [PermissionController::class, 'assignStore'])->name('permissions.assignStore');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/data-import', [DataImportController::class, 'index'])->name('data-import.index');
    Route::post('/data-import/{type}', [DataImportController::class, 'import'])->name('data-import.perform');
    Route::get('data/view/{id}', [DataViewController::class, 'view'])->name('data.view');
    Route::delete('data/delete/{id}/{type}', [DataViewController::class, 'delete'])->name('data.delete');
    Route::get('data/export/{id}', [DataViewController::class, 'export'])->name('data.export');
});


