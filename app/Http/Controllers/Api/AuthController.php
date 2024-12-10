<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class AuthController extends Controller
{
    //Реєстрація користувача
    public function register(Request $request)
    {
        // Валідатор даних
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validateUser->errors()
            ], 422);
        }

        // Створення користувача
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Генерація токена
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            // 'user' => $user,
            'token' => $token,
        ], 201);
    }

    //Логування користувача
    public function login(Request $request)
    {
        // Валідатор даних
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validateUser->errors()
            ], 422);
        }

        // Перевірка користувача:
        if (!auth()->attemp($request->only('email', 'password'))) {
            return response()->json([
                'message' => ' Credentials not match. Incorrect login or password.'
            ], 401);
        }
        //отримати цього авторизованого користувача
        $user = User::where('email', $request->email)->first();

        // Генерація токена, який буде використовуватить у наступних запитах з axios
        return   response()->json([ 
            // 'message' => 'Login successful.',
            // 'user' => $user,     
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
}
