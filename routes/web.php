<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BbpsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [BbpsController::class, 'index'])->name('bbps.index');
Route::post('/bbps/fetch', [BbpsController::class, 'fetchBill'])->name('bbps.fetch');
Route::post('/bbps/fetch-status', [BbpsController::class, 'fetchStatus'])->name('bbps.fetch-status');