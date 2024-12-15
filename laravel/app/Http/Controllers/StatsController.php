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
            ->select('users.nickname as name', DB::raw('COUNT(*) as total_victories'))
            ->whereNotNull('games.winner_user_id')
            ->where('games.type', 'M')
            ->groupBy('games.winner_user_id', 'users.nickname')
            ->orderBy('total_victories', 'desc')
            ->limit(5)
            ->get();
        return response()->json($users, 200);
    }

    public function totalPurchasesByWeek(){
        $purchases = DB::table('transactions')
            ->selectRaw('count(*) as count, WEEK(transaction_datetime) as week, YEAR(transaction_datetime) as year')
            ->whereRaw('YEAR(transaction_datetime) = YEAR(SYSDATE())')
            ->groupBy('week')
            ->groupBy('year')
            ->orderBy('year','asc')
            ->orderBy('week','asc')
            ->get();

        return response()->json($purchases, 200);
    }

    public function numberOfPlayers() {
        $players = User::All()
            ->where('deleted_at', null)
            ->count();
        return response()->json($players, 200);
    }

    public function totalGames(){
        $games = Game::where('status', 'E')
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
}
