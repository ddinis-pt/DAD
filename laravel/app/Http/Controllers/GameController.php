<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaveGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Http\Requests\UpdateTimeRequest;
use App\Http\Requests\MultiplayerGameRequest;
use App\Http\Resources\GameResource;
use Illuminate\Validation\ValidationException;
use App\Models\Game;
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
        $game = new Game();
        $game->created_user_id = $request->validated()["created_user_id"];
        $game->type = $request->validated()["type"];
        $game->status = $request->validated()["status"];
        $game->board_id = $request->validated()["board_id"];
        if (array_key_exists("began_at", $request->validated())) {
            $game->began_at = $request->validated()["began_at"];
        }
        if(array_key_exists("ended_at", $request->validated())){
            $game->ended_at = $request->validated()["ended_at"];
        }
        if(array_key_exists("total_time", $request->validated())){
            $game->total_time = $request->validated()["total_time"];
        } else {
            $game->total_time = 0;
        }
        if(array_key_exists("total_turns_winner", $request->validated())){
            $game->total_turns_winner = $request->validated()["total_turns_winner"];
        }
        $game->save();
        return response()->json(new GameResource($game), 201);
    }

    public function updateStatus(UpdateGameRequest $request, int $game_id)
    {
        $game = Game::findOrFail($game_id);
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
            case "PE":
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
                    $game->player2 = $request->user()->id;
                }
                break;
            case "PL":
                if ($newStatus == "E") {
                    $winner_user_id = null;
                    if (array_key_exists("winner_user_id", $data)) {
                        $winner_user_id = $data["winner_user_id"];
                        if ($winner_user_id != null) {
                            if (
                                $winner_user_id != $data["player1"] &&
                                $winner_user_id != $data["player2"]
                            ) {
                                throw ValidationException::withMessages([
                                    "winner_id" =>
                                    "Cannot change game #" .
                                        $game->id .
                                        " status from 'playing' to 'ended', because the winner id is invalid!\nPlayer1 - " . $game->player1 . "\nPlayer2 - " . $game->player2 . "Game - " . $game . "Winner - " . $winner_user_id,
                                ]);
                            }
                            $game->total_turns_winner = $data["total_turns_winner"];
                        }
                    }
                    $game->status = $newStatus;
                    $game->winner_user_id = $winner_user_id;
                    $game->ended_at = $data["ended_at"];
                    $game->total_time = $data["total_time"];
                } else {
                    if(array_key_exists("ended_at", $data)){
                        $game->ended_at = $data["ended_at"];
                    }
                    $game->status = $newStatus;
                }
                break;
            case "I":
                throw ValidationException::withMessages([
                    "status" =>
                    "Cannot change game #" .
                        $game->id .
                        " status because it already has been interrupted!",
                ]);
                break;
            case "E":
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
#TAES
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

    public function getTop3ByTimeGlobal()
    {
        return response()->json(Game::where('status', 'E')
            ->where('games.type', 'S')
            ->join('users', 'games.created_user_id', '=', 'users.id')
            ->orderBy('total_time', 'asc')
            ->orderBy('total_turns_winner', 'asc')
            ->take(3)
            ->get(), 200);
    }

    public function getTop3ByTurnsGlobal()
    {
        return response()->json(Game::where('status', 'E')
            ->where('games.type', 'S')
            ->join('users', 'games.created_user_id', '=', 'users.id')
            ->orderBy('total_turns_winner', 'asc')
            ->orderBy('total_time', 'asc')
            ->take(3)
            ->get(), 200);
    }


    #DAD
    public function getTop3ByTime(Request $request,$board)
    {
        $user_id = $request->user()->id;

        return response()->json(Game::where('status', 'E')
            ->where('games.type', 'S')
            ->where('created_user_id', '=', $user_id)
            ->where('board_id', '=', $board)
            ->orderBy('total_time', 'asc')
            ->select('total_time')
            ->take(3)
            ->get(),200);
    }
    public function getTop3ByTurns(Request $request,$board)
    {
        $user_id = $request->user()->id;

        return response()->json(Game::where('status', 'E')
            ->where('games.type', 'S')
            ->where('created_user_id', '=', $user_id)
            ->where('board_id', '=', $board)

            ->orderBy('total_turns_winner', 'asc')
            ->orderBy('total_time', 'asc')
            ->select('total_turns_winner')
            ->take(3)
            ->get(),200);
    }
    public function getMultiplayerVictories(Request $request)
    {
        return response()->json(Game::where('status', 'E')
            ->where('type', 'M')
            ->where('winner_user_id', '=', $request->user()->id)
            ->orderBy('ended_at', 'desc')
            ->count(), 200);
    }
    public function getMultiplayerLosses(Request $request)
    {
        return response()->json(Game::where('status', 'E')
            ->where('type', 'M')
            ->where('created_user_id', '=', $request->user()->id)
            ->where('winner_user_id', '!=', $request->user()->id)
            ->orderBy('ended_at', 'desc')
            ->count(), 200);
    }
    public function getTop5PlayersMostVictories()
    {
        return response()->json(Game::where('status', 'E')
            ->where('games.type', 'M')
            ->join('users', 'games.winner_user_id', '=', 'users.id')
            ->select('users.nickname', DB::raw('count(*) as victories'))
            ->groupBy('users.nickname')
            ->orderBy('victories', 'desc')
            ->take(5)
            ->get(), 200);
    }
    public function getTop1byTime($board)
    {
        return response()->json(Game::where('status', 'E')
            ->where('games.type', 'S')
            ->where('board_id', '=', $board)
            ->join('users', 'games.created_user_id', '=', 'users.id')
            ->orderBy('total_time', 'asc')
            ->select('total_time', 'users.nickname')
            ->take(1)
            ->get(), 200);
    }
    public function getTop1byTurns($board)
    {
        return response()->json(Game::where('status', 'E')
            ->where('games.type', 'S')
            ->where('board_id', '=', $board)
            ->join('users', 'games.created_user_id', '=', 'users.id')
            ->orderBy('total_turns_winner', 'asc')
            ->select('total_turns_winner', 'users.nickname')
            ->take(1)
            ->get(), 200);
    }

    public function getAllGames()
    {
        return response()->json(Game::all(), 200);
    }

    public function insertMultiplayerGame(MultiplayerGameRequest $request) {
        $result = DB::insert('INSERT INTO multiplayer_games_played (user_id, game_id, player_won, pairs_discovered) VALUES (?, ?, ?, ?)', [
            $request->validated()["user_id"],
            $request->validated()["game_id"],
            $request->validated()["player_won"],
            $request->validated()["pairs_discovered"]
        ]);
        if(!$result) {
            return response()->json(['message' => 'Error inserting multiplayer game'], 500);
        }
        return response()->json(['message' => 'Multiplayer game inserted'], 201);
    }

}
