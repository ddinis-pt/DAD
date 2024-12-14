<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StatsController extends Controller
{
    public function getUsersCountByMonth()
    {
        $users = User::selectRaw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year')
            ->groupBy('month')
            ->groupBy('year')
            ->orderBy('year','asc')
            ->orderBy('month','asc')
            ->get();

        return response()->json($users, 200);
    }
}
