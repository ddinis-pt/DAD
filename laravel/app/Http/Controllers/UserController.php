<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyCoinsRequest;
use App\Http\Requests\ImageRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Error;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function allUsers()
    {
        return response()->json(User::withTrashed()->get(), 200);
    }

    public function show($id)
    {
        return response()->json(User::withTrashed()->findOrFail($id), 200);
    }

    public function getImagePath(ImageRequest $request)
    {
        $path = $request->file('photo')->store('photos', 'public');
        $caminho = explode('/', $path);
        return response()->json(['photo_filename' => $caminho[1]], 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        if ($request->user()->id != $id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::find($id);
        $user->update($request->validated());
        return response()->json($user);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        if ($request?->user()?->id != $id) {
            return response()->json(['message' => $request?->user()?->id], 401);
        }
        if ($request?->user()?->id == $id && $request?->user()?->type == 'A') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $user->tokens()->delete();
        DB::update('update users set brain_coins_balance = 0 where id = ?', [$id]);
        $user->delete();
        return response()->json(null, 204);
    }

    public function block(Request $request, $id)
    {
        $user = User::find($id);
        if ($request?->user()?->id != $id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if ($request?->user()?->id == $id && $request?->user()?->type == 'A') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        DB::update('update users set blocked = 1 where id = ?', [$id]);
        return response()->json(null, 204);
    }

    public function spendCoins(Request $request, int $value)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'Error fetching the user'], 200);
            }
            $user->decrement('brain_coins_balance', $value);
            $user->save();
            return response()->json(['message' => [`Balance decreased to $user->brain_coins_balance`]], 200);
        } catch (Error $e) {
            return response()->json(['message' => 'Error' + $e], 500);
        }
    }

    public function buyCoins(BuyCoinsRequest $request)
    {
        $type = $request->validated()['type'];
        switch ($type) {
            case 'PAYPAL':
                if (preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/", $request->validated()['reference']) == 0) {
                    return response()->json(['message' => 'Invalid reference'], 500);
                }
                $pedido = Http::post('https://dad-202425-payments-api.vercel.app/api/debit', $request->validated());
                $responseBody = json_decode($pedido->body(), true);
                if ($pedido->status() == 201) {
                    $user = $request->user();
                    $user->increment('brain_coins_balance', $request->validated()['value'] * 10);
                    $user->save();
                    return response()->json(['message' => 'Coins bought successfully'], 201);
                } else if ($pedido->status() == 422) {
                    $status = $responseBody['status'] ?? 'Unknown';
                    $description = $responseBody['message'] ?? 'An error occurred';

                    return response()->json([
                        'message' => 'API Error',
                        'status' => $status,
                        'description' => $description,
                    ], $pedido->status());
                } else {
                    return response()->json(['message' => 'Error buying coins'], 500);
                }
                break;
            case 'MBWAY':
                if (preg_match("/^9\d{8}$/", $request->validated()['reference']) == 0) {
                    return response()->json(['message' => 'Invalid reference'], 500);
                }
                $pedido = Http::post('https://dad-202425-payments-api.vercel.app/api/debit', $request->validated());
                if ($pedido->status() == 201) {
                    $user = $request->user();
                    $user->increment('brain_coins_balance', $request->validated()['value'] * 10);
                    $user->save();
                    return response()->json(['message' => 'Coins bought successfully'], 201);
                } else if ($pedido->status() == 422) {
                    return response()->json(['message' => $pedido->body()['message']], $pedido->status());
                } else {
                    return response()->json(['message' => 'Error buying coins'], 500);
                }
                break;
            case 'IBAN':
                if (preg_match("/^[A-Z]{2}\d{23}$/", $request->validated()['reference']) == 0) {
                    return response()->json(['message' => 'Invalid reference'], 500);
                }
                $pedido = Http::post('https://dad-202425-payments-api.vercel.app/api/debit', $request->validated());
                if ($pedido->status() == 201) {
                    $user = $request->user();
                    $user->increment('brain_coins_balance', $request->validated()['value'] * 10);
                    $user->save();
                    return response()->json(['message' => 'Coins bought successfully'], 201);
                } else if ($pedido->status() == 422) {
                    return response()->json(['message' => $pedido->body()['message']], $pedido->status());
                } else {
                    return response()->json(['message' => 'Error buying coins'], 500);
                }
                break;
            case 'MB':
                if (preg_match("/^\d{5}-\d{9}/", $request->validated()['reference']) == 0) {
                    return response()->json(['message' => 'Invalid reference'], 500);
                }
                $pedido = Http::post('https://dad-202425-payments-api.vercel.app/api/debit', $request->validated());
                if ($pedido->status() == 201) {
                    $user = $request->user();
                    $user->increment('brain_coins_balance', $request->validated()['value'] * 10);
                    $user->save();
                    return response()->json(['message' => 'Coins bought successfully'], 201);
                } else if ($pedido->status() == 422) {
                    return response()->json(['message' => $pedido->body()['message']], $pedido->status());
                } else {
                    return response()->json(['message' => 'Error buying coins'], 500);
                }
                break;
            case 'VISA':
                if (preg_match("/^4\d{15}$/", $request->validated()['reference']) == 0) {
                    return response()->json(['message' => 'Invalid reference'], 500);
                }
                $pedido = Http::post('https://dad-202425-payments-api.vercel.app/api/debit', $request->validated());
                if ($pedido->status() == 201) {
                    $user = $request->user();
                    $user->increment('brain_coins_balance', $request->validated()['value'] * 10);
                    $user->save();
                    return response()->json(['message' => 'Coins bought successfully'], 201);
                } else if ($pedido->status() == 422) {
                    return response()->json(['message' => $pedido->body()['message']], $pedido->status());
                } else {
                    return response()->json(['message' => 'Error buying coins'], 500);
                }
                break;
            default:
                break;
        }
    }

    public function winCoins(Request $request, int $value)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'Error fetching the user'], 200);
            }
            $user->increment('brain_coins_balance', $value);
            $user->save();
            return response()->json(['message' => [`Balance increased to $user->brain_coins_balance`]], 200);
        } catch (Error $e) {
            return response()->json(['message' => 'Error' + $e], 500);
        }
    }

}
