<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        $validate = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password'])
        ]);

        $token = $user->createToken('userToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ],201);
    }

    public function login(Request $request){
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $validate['email'])->first();

        if(!$user){
            throw ValidationException::withMessages([
                "message" => "something going wrong"
            ]);
        }

        if(!Hash::check($validate['password'], $user->password)){
            throw ValidationException::withMessages([
                'message' => 'something going wrong'
            ]);
        }

        $token = $user->createToken('userToken')->plainTextToken;

        return response()->json([
            "Logined" => $user,
            'token' => $token
        ],200);
    }
}
