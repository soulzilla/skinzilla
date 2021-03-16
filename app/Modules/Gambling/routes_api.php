<?php

use App\Modules\Gambling\Controllers\GamblingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'jwt'], function () {
    Route::apiResource('gamblings', 'GamblingController');
});

Route::get('/gambling/one/{id}', [GamblingController::class, 'one']);
Route::get('/gambling/top', [GamblingController::class, 'top']);
Route::get('/gambling/list', [GamblingController::class, 'all']);
