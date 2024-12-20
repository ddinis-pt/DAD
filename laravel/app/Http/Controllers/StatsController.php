<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Game;

class StatsController extends Controller
{
    public function getUsersCountByMonth()
    {
        $users = User::selectRaw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year')
            ->whereRaw('YEAR(created_at) = YEAR(SYSDATE())')
            ->groupBy('month')
            ->groupBy('year')
            ->orderBy('year','asc')
            ->orderBy('month','asc')
            ->get();

        return response()->json($users, 200);
    }

    public function getTop5Winners()
    {
        $users = DB::table('games')
            ->join('users', 'games.winner_user_id', '=', 'users.id')
            ->select('users.name as name', DB::raw('COUNT(*) as total_victories'))
            ->whereNotNull('games.winner_user_id')
            ->where('games.type', 'M')
            ->groupBy('games.winner_user_id', 'users.nickname')
            ->orderBy('total_victories', 'desc')
            ->limit(5)
            ->get();
        return response()->json($users, 200);
    }

    public function getTop5Losers()
    {
        $users = DB::table('multiplayer_games_played')
            ->join('users', 'multiplayer_games_played.user_id', '=', 'users.id')
            ->select('users.name as name', DB::raw('COUNT(*) as total_defeats'))
            ->whereNotNull('multiplayer_games_played.user_id')
            ->where('multiplayer_games_played.player_won', 0)
            ->groupBy('users.nickname')
            ->orderBy('total_defeats', 'desc')
            ->limit(5)
            ->get();
        return response()->json($users, 200);
    }

    public function getTop5Buyers()
    {
        $users = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('users.name as name', DB::raw('SUM(transactions.euros) as total_bought'))
            ->where('transactions.type', 'P')
            ->where('brain_coins', '>' , 0)
            ->groupBy('transactions.user_id', 'users.nickname')
            ->orderBy('total_bought', 'desc')
            ->limit(5)
            ->get();
        return response()->json($users, 200);
    }

    public function getTop5Spenders(){
        $users = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('SUM(transactions.brain_coins) as total_spent'))
            ->where('transactions.type', 'I')
            ->where('brain_coins', '<', 0)
            ->groupBy('transactions.user_id', 'users.nickname')
            ->orderBy('total_spent', 'asc')
            ->limit(5)
            ->get();
        return response()->json($users, 200);
    }

    public function totalPurchasesByWeek(){
        $purchases = DB::table('transactions')
            ->selectRaw('count(*) as count, WEEK(transaction_datetime) as week, YEAR(transaction_datetime) as year')
            ->whereRaw('YEAR(transaction_datetime) = YEAR(SYSDATE())')
            ->where('type', 'P')
            ->groupBy('week')
            ->groupBy('year')
            ->orderBy('year','asc')
            ->orderBy('week','asc')
            ->get();
        return response()->json($purchases, 200);
    }

    public function totalGamesByStatus(){
        $games = Game::selectRaw('count(*) as count, status')
            ->groupBy('status')
            ->get();

        return response()->json($games, 200);
    }
 
    public function getGlobalScoreboard(){
        $games = Db::table('games')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('status', 'E')
            ->where('type', 'M')
            ->groupBy('winner_user_id')
            ->orderBy('count','desc')
            ->limit(10)
            ->get();
        return response()->json($users, 200);
    }

    public function numberOfPlayers() {
        $players = User::All()
            ->where('type','P')
            ->count();
        return response()->json($players, 200);
    }

    public function totalGames(){
        $games = Game::where('status', 'E')
            ->count();
        return response()->json($games, 200);
    }

    public function totalMultiplayerGames(){
        $games = Game::where('status', 'E')
            ->where('type', 'M')
            ->count();
        return response()->json($games, 200);
    }

    public function gamesPlayedLastWeek()
    {
        $games = Game::where('status', 'E')
            ->whereBetween('ended_at', [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek(),
            ])
            ->count();
        return response()->json($games, 200);
    }

    public function gamesPlayedLastMonth(){
        $games = Game::where('status', 'E')
            ->whereBetween('ended_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth(),
            ])
            ->count();
        return response()->json($games, 200);
    }

    public function usersByBlocked(){
        $users = DB::table('users')
            ->select('blocked', DB::raw('count(*) as count'))
            ->where('deleted_at', null)
            ->where('type', 'P')
            ->groupBy('blocked')
            ->get();

        $usersTrashed = User::onlyTrashed()
            ->where('type','P')
            ->count();

        $users->push([
            'blocked' => 'trashed',
            'count' => $usersTrashed
        ]);
        
        return response()->json($users, 200);
    }

    public function getAdmins(){
        $users = User::All()
            ->where('type','A')
            ->count();
        return response()->json($users, 200);
    }

    public function totalGamesByBoard(){
        $gamesByBoard = DB::table('games')
            ->join('boards', 'games.board_id', '=', 'boards.id')
            ->select(DB::raw('CONCAT(boards.board_cols, "x", boards.board_rows) as board_size'), DB::raw('COUNT(*) as total_games'))
            ->groupBy('board_size')
            ->get();
        return response()->json($gamesByBoard, 200);
    }

    public function totalGamesByTypeAndMonth(){
        $jogosPorTipoPorMes = DB::table('games')
        ->select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),'type',DB::raw('COUNT(*) as total_games')
        )
        ->whereRaw('YEAR(created_at) = YEAR(SYSDATE())')
        ->groupBy('month', 'type')
        ->orderBy('month')
        ->get();

        $resultado = [];
        foreach ($jogosPorTipoPorMes as $entry) {
            $mes = $entry->month;
            $tipo = $entry->type;
            $total = $entry->total_games;

            if (!isset($resultado[$mes])) {
                $resultado[$mes] = [
                    'month' => $mes,
                    'singleplayer' => 0,
                    'multiplayer' => 0,
                ];
            }

            if ($tipo === 'S') {
                $resultado[$mes]['singleplayer'] = $total;
            } elseif ($tipo === 'M') {
                $resultado[$mes]['multiplayer'] = $total;
            }
        }

        $dadosGrafico = array_values($resultado);
        return response()->json($dadosGrafico);
    }

    public function totalPurchases(){
        $purchases = DB::table('transactions')
            ->where('type','P')
            ->count();
        return response()->json($purchases, 200);
    }

    public function totalTransactions(){
        $transactions = DB::table('transactions')
            ->count();
        return response()->json($transactions, 200);
    }

    public function totalMoney(){
        $money = DB::table('transactions')
            ->where('type','P')
            ->sum('euros');
        return response()->json($money, 200);
    }

    public function totalBrainCoins(){
        $brainCoins = User::All()
            ->sum('brain_coins_balance');
        return response()->json($brainCoins, 200);
    }

    public function activeUsers(){
        $activeUsers = User::where('updated_at', '>=', now()->startOfMonth())
            ->where('updated_at', '<', now()->startOfMonth()->addMonth())
            ->count();
        return response()->json($activeUsers, 200);
    }

    public function brainCoinsUsed(){
        $brainCoins = DB::table('transactions')
            ->select(DB::raw('SUM(brain_coins) * -1 as total_brain_coins'), 
                    DB::raw('WEEKDAY(transaction_datetime) + 1 as day_of_week')
            )
            ->where('type','I')
            ->where('brain_coins', '<', 0)
            ->orderBy('day_of_week')
            ->groupBy('day_of_week')
            ->get();
        return response()->json($brainCoins, 200);
    }
}