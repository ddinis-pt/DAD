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

    Route::get('/spend/{value}', [UserController::class, "spendCoins"]);
    Route::post('/buy/', [UserController::class, "buyCoins"]);
    Route::get('/win/{value}', [UserController::class, "winCoins"]);

    Route::get('/leaderboard/time', [GameController::class, "getTopTenByTime"]);
    Route::get('/leaderboard/turns', [GameController::class, "getTopTenByTurns"]);

    Route::get('/leaderboard/personal/time/{board}', [GameController::class, "getTop3ByTime"]);
    Route::get('/leaderboard/personal/turns/{board}', [GameController::class, "getTop3ByTurns"]);

    Route::get('games/multiplayer/wins', [GameController::class, "getMultiplayerVictories"]);
    Route::get('games/multiplayer/losses', [GameController::class, "getMultiplayerLosses"]);



    Route::get('/games/singleplayer/{id}', [GameController::class, "getAllSingleGamesByUser"]);


    Route::get('/games/multiplayer/{id}', [GameController::class, "getMultiplayerGamesByUser"]);
    Route::get('/games/winner/{id}', [GameController::class, "getWinnerName"]);
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{game}', [GameController::class, 'show']);
    Route::post('/games', [GameController::class, 'store']);
    Route::patch('/games/{game}', [GameController::class, 'updateStatus']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);
});

//Auth
Route::post('/auth/login', [AuthController::class, "login"]);
Route::post('/auth/register', [AuthController::class, "register"]);
Route::post('/auth/loginTAES', [AuthController::class, "loginTAES"]);

//Users
Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{id}', [UserController::class, "show"]);

//Games


Route::get('/leaderboard/top/wins', [GameController::class, "getTop5PlayersMostVictories"]);
Route::get('/leaderboard/turns/global', [GameController::class, "getTop3ByTurnsGlobal"]);
Route::get('/leaderboard/global/time/{board}', [GameController::class, "getTop1byTime"]);
Route::get('/leaderboard/global/turns/{board}', [GameController::class, "getTop1byTurns"]);

Route::post('/images', [UserController::class, "getImagePath"]);
