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
    Route::put('/spend/{value}', [UserController::class, "spendCoins"]);
    Route::post('/buy/', [UserController::class, "buyCoins"]);
    Route::put('/win/{value}', [UserController::class, "winCoins"]);
    Route::put('/users/{id}/win/{value}', [UserController::class, "winCoinsFor"]);
    Route::put('/users/{id}/spend/{value}', [UserController::class, "spendCoinsFor"]);
    Route::get('/leaderboard/time', [GameController::class, "getTopTenByTime"]);
    Route::get('/leaderboard/turns', [GameController::class, "getTopTenByTurns"]);
    Route::get('/leaderboard/personal/time/{board}', [GameController::class, "getTop3ByTime"]);
    Route::get('/leaderboard/personal/turns/{board}', [GameController::class, "getTop3ByTurns"]);
    Route::get('games/multiplayer/wins', [GameController::class, "getMultiplayerVictories"]);
    Route::get('games/multiplayer/losses', [GameController::class, "getMultiplayerLosses"]);
    Route::get('/games/singleplayer/{id}', [GameController::class, "getAllSingleGamesByUser"]);
    Route::get('/games/multiplayer/{id}', [GameController::class, "getMultiplayerGamesByUser"]);
    Route::get('/games/winner/{id}', [GameController::class, "getWinnerName"]);
    Route::get('/games/all', [GameController::class, 'getAllGames']);
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{id}', [GameController::class, 'show']);
    Route::post('/games', [GameController::class, 'store']);
    Route::patch('/games/{game}', [GameController::class, 'updateStatus']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);
    Route::put('/games/{game_id}', [GameController::class, 'updateStatus']);
    Route::post('/games/multiplayer', [GameController::class, 'insertMultiplayerGame']);
    Route::post('/registerTransaction', [UserController::class, 'registerTransaction']);

    Route::middleware('can:isAdmin')->group(function () {
        Route::put('/users/{id}/update', [UserController::class, "updateByAdmin"]);
        Route::delete('/users/{id}/admin', [UserController::class, "destroyByAdmin"]);
        Route::put('/users/{id}/block', [UserController::class, "block"]);
        Route::put('/users/{id}/unblock', [UserController::class, "unblock"]);
        Route::get('/stats/winners/top5', [StatsController::class, 'getTop5Winners']);
        Route::get('/stats/losers/top5', [StatsController::class, 'getTop5Losers']);
        Route::get('/stats/buyers/top5', [StatsController::class, 'getTop5Buyers']);
        Route::get('/stats/spenders/top5', [StatsController::class, 'getTop5Spenders']);
        Route::get('/users/all', [UserController::class, "allUsers"]);
    });
});

Route::get('/stats/users/total', [StatsController::class, 'numberOfPlayers']);
Route::get('/stats/games/total', [StatsController::class, 'totalGames']);
Route::get('/stats/games/lastWeek', [StatsController::class, 'gamesPlayedLastWeek']);
Route::get('/stats/games/lastMonth', [StatsController::class, 'gamesPlayedLastMonth']);
Route::get('/stats/games/total/status', [StatsController::class, 'totalGamesByStatus']);
Route::get('stats/users/blocked', [StatsController::class, 'usersByBlocked']);
Route::get('stats/games/multiplayer', [StatsController::class, 'totalMultiplayerGames']);
Route::get('stats/games/total/board', [StatsController::class, 'totalGamesByBoard']);
Route::get('stats/games/total/typeAndMonth', [StatsController::class, 'totalGamesByTypeAndMonth']);
Route::get('stats/purchases/total', [StatsController::class, 'totalPurchases']);
Route::get('stats/transactions/total', [StatsController::class, 'totalTransactions']);
Route::get('stats/purchases/totalMoney', [StatsController::class, 'totalMoney']);
Route::get('stats/users/totalBrainCoins', [StatsController::class, 'totalBrainCoins']);
Route::get('stats/users/active', [StatsController::class, 'activeUsers']);
Route::get('stats/transactions/brainCoinsUsed', [StatsController::class, 'brainCoinsUsed']);
Route::get('/stats/users/admins', [StatsController::class, 'getAdmins']);
Route::get('/stats/users/month', [StatsController::class, 'getUsersCountByMonth']);
Route::get('/stats/purchases/week', [StatsController::class, 'totalPurchasesByWeek']);

Route::post('/auth/login', [AuthController::class, "login"]);
Route::post('/auth/register', [AuthController::class, "register"]);
Route::post('/auth/registeradmin', [AuthController::class, "registerAdmin"]);
Route::post('/auth/loginTAES', [AuthController::class, "loginTAES"]);

Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{id}', [UserController::class, "show"]);

Route::get('/leaderboard/top/wins', [GameController::class, "getTop5PlayersMostVictories"]);
Route::get('/leaderboard/turns/global', [GameController::class, "getTop3ByTurnsGlobal"]);
Route::get('/leaderboard/global/time/{board}', [GameController::class, "getTop1byTime"]);
Route::get('/leaderboard/global/turns/{board}', [GameController::class, "getTop1byTurns"]);

Route::post('/images', [UserController::class, "getImagePath"]);

Route::get('/transactions', [UserController::class, 'getTransactions']);
