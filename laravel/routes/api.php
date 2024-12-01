<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/me', function (Request $request) {
        return $request->user();
    });

    Route::post('/auth/logout', [AuthController::class, "logout"]);
    Route::post('/auth/refreshtoken', [AuthController::class, "refreshtoken"]);
});

Route::post('/auth/login', [AuthController::class, "login"]);

Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{id}', [UserController::class, "show"]);

Route::get('/games', [GameController::class, "index"]);
Route::get('/games/{id}', [GameController::class, "show"]);
Route::post('/games', [GameController::class, "store"]);
Route::get('/games/user/{userId}', [GameController::class, "getAllGamesByUser"]);
