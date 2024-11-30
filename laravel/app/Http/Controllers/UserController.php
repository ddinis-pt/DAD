<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('users')->get(), 200);
    }

    public function show($id)
    {
        return response()->json(DB::table('users')->find($id), 200);
    }
}
