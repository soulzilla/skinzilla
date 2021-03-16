<?php

use App\Modules\Comment\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/comments/entity', [CommentController::class, 'entity']);

Route::group(['middleware' => 'jwt'], function () {
    Route::apiResource('comments', 'CommentController');
});
