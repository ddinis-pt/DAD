<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function show($id)
    {
        return response()->json(User::findOrFail($id), 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        if($request->user()->id != $id){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::find($id);
        $user->update($request->validated());
        return response()->json($user);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        if($request?->user()?->id != $id){
            return response()->json(['message' => $request?->user()?->id], 401);
        }
        if($request?->user()?->id == $id && $request?->user()?->type == 'A'){
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $user->tokens()->delete();
        DB::update('update users set brain_coins_balance = 0 where id = ?', [$id]);
        $user->delete();
        return response()->json(null, 204);
    }
}
