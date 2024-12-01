<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\SaveGameRequest;
use App\Models\Game;

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
        ->orderBy('ended_at', 'desc')
        ->get(), 200);
    }
}
