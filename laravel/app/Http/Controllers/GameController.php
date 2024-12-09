<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaveGameRequest;
use App\Models\Game;
use Error;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function index()
    {
        return response()->json(Game::where('status', 'e')
            ->orderBy('total_time', 'asc')
            ->take(100)
            ->get(), 200);
    }

    public function show($id)
    {
        return response()->json(Game::findOrFail($id), 200);
    }

    public function store(SaveGameRequest $request)
    {
        $game = Game::create($request->validated());
        return response()->json($game, 201);
    }

    public function getAllSinglePlayerGames()
    {
        return response()->json(Game::where('type', 'S')->get(), 200);
    }

    public function getAllMultiPlayerGames()
    {
        return response()->json(Game::where('type', 'M')->get(), 200);
    }

    public function getAllGamesByUser($userId)
    {
        return response()->json(Game::where('created_user_id', $userId)
            ->where('type', 'S')
            ->orderBy('ended_at', 'desc')
            ->get(), 200);
    }

    public function getAllSingleGamesByUser($userId)
    {
        return response()->json(Game::where('created_user_id', $userId)
            ->where('type', 'S')
            ->orderBy('ended_at', 'desc')
            ->get(), 200);
    }

    public function getTopTenByTime(Request $request)
    {
        return response()->json(Game::where('status', 'E')
            ->where('type', 'S')
            ->where('created_user_id', '=', $request->user()->id)
            ->orderBy('total_time', 'asc')
            ->take(10)
            ->get(), 200);
    }

    public function getTopTenByTurns(Request $request)
    {
        return response()->json(Game::where('status', 'E')
            ->where('type', 'S')
            ->where('created_user_id', '=', $request->user()->id)
            ->orderBy('total_turns_winner', 'asc')
            ->take(10)
            ->get(), 200);
    }

    public function getTopTenByTimeGlobal()
    {
        return response()->json(Game::where('status', 'E')
            ->where('type', 'S')
            ->orderBy('total_time', 'asc')
            ->orderBy('total_turns_winner', 'asc')
            ->take(3)
            ->get(), 200);
    }

    public function getTopTenByTurnsGlobal()
    {
        return response()->json(Game::where('status', 'E')
            ->where('type', 'S')
            ->orderBy('total_turns_winner', 'asc')
            ->orderBy('total_time', 'asc')
            ->take(3)
            ->get(), 200);
    }
}
