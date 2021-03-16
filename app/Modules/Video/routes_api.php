<?php

use App\Modules\Video\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'jwt'], function () {
    Route::apiResource('videos', 'VideoController');
});

Route::get('/video', [VideoController::class, 'latest']);
