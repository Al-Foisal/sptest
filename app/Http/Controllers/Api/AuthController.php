<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login(Request $request) {
        try {
            $request->validate([
                'email'    => 'required',
                'password' => 'required|min:8',
            ]);

            if (!Auth::attempt([
                'email'    => $request->email,
                'password' => $request->password,
            ])) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthorized account',
                ]);
            }

            $user        = Auth::user();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status'       => true,
                'access_token' => $tokenResult,
                'token_type'   => 'Bearer',
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status'  => false,
                'message' => 'Error in Login',
                'error'   => $error,
            ]);
        }

    }

    public function signup(Request $request) {
        $request->validate([
            'email'    => 'required|unique:users|email',
            'password' => 'required|min:8',
        ]);
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'lat'      => $request->lat,
            'long'     => $request->long,
        ]);

        return response()->json([
            'status'  => true,
            'user'    => $user,
            'message' => 'Registration successful.',
        ]);
    }

}
