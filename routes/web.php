<?php

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
