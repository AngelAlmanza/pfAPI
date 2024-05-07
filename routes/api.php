<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VeterinaryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::post('/create', [PostController::class, 'create']);
        Route::put('/{id}/update', [PostController::class, 'update']);
        Route::delete('/{id}/destroy', [PostController::class, 'delete']);
    });

    Route::prefix('veterinaries')->group(function () {
        Route::get('/', [VeterinaryController::class, 'index']);
        Route::get('/{id}', [VeterinaryController::class, 'show']);
        Route::post('/create', [VeterinaryController::class, 'create']);
        Route::put('/{id}/update', [VeterinaryController::class, 'update']);
        Route::delete('/{id}/destroy', [VeterinaryController::class, 'delete']);
    });
});
