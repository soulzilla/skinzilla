<?php

use App\Modules\Review\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/review/top', [ReviewController::class, 'top']);

Route::group(['middleware' => 'jwt'], function () {
    Route::apiResource('reviews', 'ReviewController');
});
