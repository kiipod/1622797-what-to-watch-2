<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [RegisterController::class, 'register'])->name('register.index');

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')->name('auth.logout');

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::patch('/{id}', [UserController::class, 'update'])->name('user.update');
});

Route::group(['prefix' => 'films'], function () {
    Route::get('/', [FilmController::class, 'index'])->name('films.index');
    Route::get('/{id}', [FilmController::class, 'show'])->name('films.show');
    Route::get('{id}/similar', [FilmController::class, 'getSimilar'])
        ->where('id', '\d+')->name('films.similar');
    Route::get('{id}/comments', [CommentController::class, 'index'])
        ->where('id', '\d+')->name('comments.index');
});

Route::prefix('films')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [FilmController::class, 'store'])->name('films.store');
    Route::patch('/{id}', [FilmController::class, 'update'])
        ->where('id', '\d+')->name('films.update');
    Route::post('/{id}/favorite', [FavoriteController::class, 'store'])
        ->where('id', '\d+')->name('favorite.store');
    Route::delete('/{id}/favorite', [FavoriteController::class, 'destroy'])
        ->where('id', '\d+')->name('favorite.destroy');
    Route::post('/{id}/comments', [CommentController::class, 'store'])
       ->where('id', 'd+')->name('comment.store');
});

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');

Route::prefix('genres')->middleware('auth:sanctum')->group(function () {
    Route::patch('/{genre}', [GenreController::class, 'update'])->name('genres.update');
});

Route::get('/favorite', [FavoriteController::class, 'index'])
    ->middleware('auth:sanctum')->name('favorite.index');

Route::prefix('comments')->middleware('auth:sanctum')->group(function () {
    Route::patch('/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::prefix('promo')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PromoController::class, 'index'])->name('promo.index');
    Route::post('/{id}', [PromoController::class, 'store'])
        ->where('id', '\d+')->name('promo.store');
});
