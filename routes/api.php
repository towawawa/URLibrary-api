<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UrlLibraries;
use App\Http\Controllers\Genres;
use App\Http\Controllers\Masters;
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

Route::get('/env-debug', function () {
    return response()->json([
        'CORS_ALLOWED_ORIGINS' => env('CORS_ALLOWED_ORIGINS'),
        'APP_ENV' => env('APP_ENV'),
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/masters', Masters\IndexController::class);

    Route::get('/genres', Genres\IndexController::class);

    Route::get('/url-libraries', UrlLibraries\IndexController::class);
    Route::get('/url-libraries/{id}', UrlLibraries\GetController::class);
    Route::put('/url-libraries/{id}', UrlLibraries\EditController::class);
    Route::put('/url-libraries/{id}/note', UrlLibraries\EditNoteController::class);
    Route::post('/url-libraries', UrlLibraries\RegisterController::class);
    Route::delete('/url-libraries/{id}', UrlLibraries\DeleteController::class);
});
