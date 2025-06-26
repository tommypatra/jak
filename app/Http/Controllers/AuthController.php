<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AturGrup;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(AuthRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('api_token')->plainTextToken;
            updateTokenUsed();
            $daftarAkses = daftarAkses($user->id);
            // dd($daftarAkses);
            $akses_grup = $daftarAkses->aturgrup[0]->grup_id;

            $respon_data = [
                'message' => 'Login successful',
                'data' => $user,
                'access_token' => $token,
                'akses_grup' => $akses_grup,
                'daftar_akses' => $daftarAkses->aturgrup,
                'token_type' => 'Bearer',
            ];
            return response()->json($respon_data, 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function tokenCek($grup_id)
    {
        $user_id = auth()->check() ? auth()->user()->id : null;
        if ($user_id) {
            $daftarAkses = daftarAkses($user_id);
            // dd($daftarAkses->aturgrup);
            $index = array_search($grup_id, array_column($daftarAkses->aturgrup->toArray(), 'grup_id'));
            if ($index !== false) {
                return response()->json(['message' => 'token valid'], 200);
            }
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            if ($request->user()->tokens()->count() > 0) {
                $request->user()->tokens()->delete();
            }
        }
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
