<?php

use App\Modules\Market\Controllers\MarketController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'jwt'], function () {
    Route::apiResource('markets', 'MarketController');
});

Route::get('/market/one/{id}', [MarketController::class, 'one']);
Route::get('/market/top', [MarketController::class, 'top']);
Route::get('/market/list', [MarketController::class, 'all']);
