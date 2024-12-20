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
    Route::put('/users/{id}', [UserController::class, "update"]);
    Route::delete('/users/{id}', [UserController::class, "destroy"]);

    Route::put('/spend/{value}', [UserController::class, "spendCoins"]);
    Route::post('/buy/', [UserController::class, "buyCoins"]);
    Route::put('/win/{value}', [UserController::class, "winCoins"]);
    Route::put('/users/{id}/win/{value}', [UserController::class, "winCoinsFor"]);
    Route::put('/users/{id}/spend/{value}', [UserController::class, "spendCoinsFor"]);

    Route::get('/leaderboard/time', [GameController::class, "getTopTenByTime"]);
    Route::get('/leaderboard/turns', [GameController::class, "getTopTenByTurns"]);

    Route::get('/games/singleplayer/{id}', [GameController::class, "getAllSingleGamesByUser"]);
    Route::get('/games/multiplayer/{id}', [GameController::class, "getMultiplayerGamesByUser"]);
    Route::get('/games/winner/{id}', [GameController::class, "getWinnerName"]);
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{id}', [GameController::class, 'show']);
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{game_id}', [GameController::class, 'updateStatus']);
    Route::post('/games/multiplayer', [GameController::class, 'insertMultiplayerGame']);
    // Route::delete('/games/{game}', [GameController::class, 'destroy']);
});

//Auth
Route::post('/auth/login', [AuthController::class, "login"]);
Route::post('/auth/register', [AuthController::class, "register"]);
Route::post('/auth/loginTAES', [AuthController::class, "loginTAES"]);

//Users
Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{id}', [UserController::class, "show"]);

//Games



Route::get('/leaderboard/time/global', [GameController::class, "getTopTenByTimeGlobal"]);
Route::get('/leaderboard/turns/global', [GameController::class, "getTopTenByTurnsGlobal"]);

Route::post('/images', [UserController::class, "getImagePath"]);
