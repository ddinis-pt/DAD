<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\StatsController;

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

    Route::get('/games/singleplayer/{id}', [GameController::class, "getAllSingleGamesByUser"]);

    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{game}', [GameController::class, 'show']);
    Route::post('/games', [GameController::class, 'store']);
    Route::patch('/games/{game}', [GameController::class, 'updateStatus']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);

    
    Route::get('/stats/users/month', [StatsController::class, 'getUsersCountByMonth']);
    Route::get('/stats/winners/top5', [StatsController::class, 'getTop5Winners']);
    Route::get('/stats/purchases/week', [StatsController::class, 'totalPurchasesByWeek']);
});

//Stats for all users
Route::get('/stats/users/total', [StatsController::class, 'numberOfPlayers']);
Route::get('/stats/games/total', [StatsController::class, 'totalGames']);
Route::get('/stats/games/lastWeek', [StatsController::class, 'gamesPlayedLastWeek']);
Route::get('/stats/games/lastMonth', [StatsController::class, 'gamesPlayedLastMonth']);

//Auth
Route::post('/auth/login', [AuthController::class, "login"]);
Route::post('/auth/register', [AuthController::class, "register"]);
Route::post('/auth/loginTAES', [AuthController::class, "loginTAES"]);

//Users
Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{id}', [UserController::class, "show"]);

//Games
Route::get('/games/user/{userId}', [GameController::class, "getAllGamesByUser"]);


Route::get('/leaderboard/time/global', [GameController::class, "getTopTenByTimeGlobal"]);
Route::get('/leaderboard/turns/global', [GameController::class, "getTopTenByTurnsGlobal"]);

Route::post('/images', [UserController::class, "getImagePath"]);
