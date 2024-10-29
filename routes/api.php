<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UrlLibraries;
use App\Http\Controllers\Genres;
use App\Http\Controllers\Me;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', LoginController::class)->name('login');
Route::get('/me', Me\GetController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/genres', Genres\IndexController::class);
    Route::get('/url-libraries', UrlLibraries\IndexController::class);
});
