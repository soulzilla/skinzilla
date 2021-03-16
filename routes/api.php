<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\CompositionsController;
use App\Http\Controllers\FavouritesController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SkinsController;
use Illuminate\Support\Facades\Route;

Route::post('/file/upload', [FileController::class, 'store']);
Route::delete('/file/delete', [FileController::class, 'destroy']);

Route::post('/rating', [RatingController::class, 'store'])->middleware(['jwt']);

Route::post('/like', [LikeController::class, 'store'])->middleware(['jwt']);
Route::post('/dislike', [LikeController::class, 'destroy'])->middleware(['jwt']);

Route::post('/inventory', [InventoryController::class, 'store'])->middleware(['jwt']);
Route::delete('/inventory', [InventoryController::class, 'destroy'])->middleware(['jwt']);
Route::get('/inventory', [InventoryController::class, 'index'])->middleware(['jwt']);

Route::get('/search', [SkinsController::class, 'index'])->middleware(['jwt']);

Route::delete('/composition/delete/{id}', [CompositionsController::class, 'destroy'])->middleware(['jwt']);
Route::delete('/composition/clear/{id}', [CompositionsController::class, 'clear'])->middleware(['jwt']);
Route::post('/composition/copy', [CompositionsController::class, 'copy'])->middleware(['jwt']);
Route::get('/composition/{id}', [CompositionsController::class, 'show']);

Route::get('/compositions/my', [CompositionsController::class, 'my'])->middleware(['jwt']);
Route::delete('/compositions/remove', [CompositionsController::class, 'remove'])->middleware(['jwt']);
Route::get('/compositions/latest', [CompositionsController::class, 'latest']);
Route::get('/compositions', [CompositionsController::class, 'index']);
Route::post('/compositions', [CompositionsController::class, 'store'])->middleware(['jwt']);
Route::post('/compositions/add', [CompositionsController::class, 'add'])->middleware(['jwt']);

Route::post('/favourites', [FavouritesController::class, 'store'])->middleware(['jwt']);
Route::delete('/favourites', [FavouritesController::class, 'destroy'])->middleware(['jwt']);
