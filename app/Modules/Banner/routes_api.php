<?php

use App\Modules\Banner\Controllers\BannerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['jwt']], function () {
    Route::apiResource('banners', 'BannerController');
});

Route::get('/banner/top', [BannerController::class, 'top']);
