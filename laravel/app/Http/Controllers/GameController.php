<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\SaveGameRequest;

class GameController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('games')->get(), 200);
    }

    public function show($id)
    {
        return response()->json(DB::table('games')->find($id), 200);
    }

    public function store(SaveGameRequest $request)
    {
        $game = DB::table('games')->insertGetId($request->validated());
        return response()->json(DB::table('games')->find($game), 201);
    }
}
