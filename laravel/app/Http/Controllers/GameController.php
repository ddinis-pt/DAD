<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaveGameRequest;
use App\Http\Requests\UpdateGameRequest;
use Illuminate\Validation\ValidationException;
use App\Models\Game;
use Error;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function index()
    {
        return response()->json(Game::where('status', 'e')
            ->orderBy('total_time', 'asc')
            ->get(), 200);
    }

    public function show($id)
    {
        return response()->json(Game::findOrFail($id), 200);
    }

    public function totalGames()
    {
        return Game::count();
    }

    public function store(SaveGameRequest $request)
    {
        $game = Game::create($request->validated());
        return response()->json($game, 201);
    }

    public function updateStatus(UpdateGameRequest $request, Game $game)
    {
        $data = $request->validated();
        $newStatus = $data["status"];
        // Only playing games can have their status changed (to ended or interrupted)
        if ($game->status != "PL") {
            throw ValidationException::withMessages([
                "status" =>
                    "Cannot change game #" .
                    $game->id .
                    " status from '" .
                    $game->status .
                    "' to '$newStatus'!",
            ]);
        }
        switch ($game->status) {
            case "P":
                if (
                    $newStatus == "PL" &&
                    $request->user()->id == $game->created_user_id
                ) {
                    return response()->json(
                        [
                            "message" =>
                                "Forbidden! Player 1 cannot start the game without player 2.",
                        ],
                        403
                    );
                }
                if ($newStatus == "E") {
                    throw ValidationException::withMessages([
                        "status" =>
                            "Cannot change game #" .
                            $game->id .
                            " status from 'pending' to 'ended'!",
                    ]);
                }
                $game->status = $newStatus;
                $game->winner_user_id = null;
                if ($newStatus == "PL") {
                    $game->custom = $data["custom"];
                }
                break;
            case "PL":
                if ($newStatus == "E") {
                    $winner_user_id = null;
                    if (array_key_exists("winner_user_id", $data)) {
                        $winner_user_id = $data["winner_user_id"];
                        if ($winner_user_id != null) {
                            if (
                                $winner_user_id != $game->player1_id &&
                                $winner_user_id != json_decode($data["custom"])->{"player2"}
                            ) {
                                throw ValidationException::withMessages([
                                    "winner_id" =>
                                        "Cannot change game #" .
                                        $game->id .
                                        " status from 'playing' to 'ended', because the winner is invalid!",
                                ]);
                            }
                        }
                    }
                    $game->status = $newStatus;
                    $game->winner_id = $winner_user_id;
                } else {
                    $game->status = $newStatus;
                }
                break;
            case "interrupted":
                throw ValidationException::withMessages([
                    "status" =>
                        "Cannot change game #" .
                        $game->id .
                        " status because it already has been interrupted!",
                ]);
                break;
            case "ended":
                throw ValidationException::withMessages([
                    "status" =>
                        "Cannot change game #" .
                        $game->id .
                        " status because it already has ended!",
                ]);
                break;
        }
        $game->save();
        return response()->json($game, 200);
    }

    public function getAllSinglePlayerGames()
    {
        return response()->json(Game::where('type', 'S')->get(), 200);
    }

    public function getAllMultiPlayerGames()
    {
        return response()->json(Game::where('type', 'M')->get(), 200);
    }

    public function getMultiplayerGamesByUser($userId)
    {
        return response()->json(Game::where('created_user_id', $userId)
            ->where('games.type', 'M')
           ->join('users', 'games.winner_user_id', '=', 'users.id')
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
    public function getWinnerName($userId)
    {
        return response()->json(Game::where('winner_user_id', $userId)
            ->join('users', 'games.winner_user_id', '=', 'users.id')
            ->select('users.name')
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
            ->where('games.type', 'S')
            ->join('users', 'games.created_user_id', '=', 'users.id')
            ->orderBy('total_time', 'asc')
            ->orderBy('total_turns_winner', 'asc')
            ->take(3)
            ->get(), 200);
    }

    public function getTopTenByTurnsGlobal()
    {
        return response()->json(Game::where('status', 'E')
            ->where('games.type', 'S')
            ->join('users', 'games.created_user_id', '=', 'users.id')
            ->orderBy('total_turns_winner', 'asc')
            ->orderBy('total_time', 'asc')
            ->take(3)
            ->get(), 200);
    }
}
