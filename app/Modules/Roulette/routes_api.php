<?php

use App\Modules\Roulette\Controllers\RouletteController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'jwt'], function () {
    Route::apiResource('roulettes', 'RouletteController');
});

Route::get('/roulette/one/{id}', [RouletteController::class, 'one']);
Route::get('/roulette/top', [RouletteController::class, 'top']);
Route::get('/roulette/list', [RouletteController::class, 'all']);
