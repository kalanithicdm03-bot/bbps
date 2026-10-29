<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BbpsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/bbps/fetch-bill', [BbpsController::class, 'fetchBill']);

Route::post('/bbps/fetch-status', [BbpsController::class, 'fetchStatus']);