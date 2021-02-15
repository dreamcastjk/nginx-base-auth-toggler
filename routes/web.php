<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () { return redirect(route('login')); })->name('welcome');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'allowed'])->group(function () {
    Route::get('base-auth', SiteController::class)->name('base.auth');

    Route::middleware('nginxConfig')->post('disable', [SiteController::class, 'disable'])->name('disable');

    Route::get('enable/{domain:id}', [SiteController::class, 'enable'])->name('enable');

    Route::get('list', [SiteController::class, 'list'])->name('domains.list');
});
