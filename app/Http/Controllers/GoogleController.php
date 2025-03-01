<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\UserController;
use App\Models\User;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends BaseController
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $user = Socialite::driver('google')->stateless()->userFromToken($request->token);

        $userInDb = User::firstOrCreate(
            ['email' => $user->getEmail()],
            [
                'name' => $user->getName(),
                'password' => bcrypt(str_random(16)), // Random password
            ]
        );

        $token = $userInDb->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function verifyGoogleToken(Request $request)
    {
        Log::info('Google token verification request received');

        $request->validate([
            'id_token' => 'required|string',
        ]);

        $client = new Google_Client([
            'client_id' => config('services.google.client_id')
        ]);

        try {
            Log::info('Attempting to verify ID token');
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json([
                    'error' => 'Invalid token provided',
                    'client_id_used' => config('services.google.client_id')
                ], 401);
            }

            Log::info('Token verification successful', ['email' => $payload['email']]);
            $request->merge(['email' => $payload['email']]);
            $request->merge(['name' => $payload['name']]);
            $request->merge(['password' => bcrypt(Str::random())]);
            $request->merge(['role' => 2]);
            $response = UserController::store($request);
dd($response);
        } catch (\Exception $e) {
            Log::error('Token verification failed', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Token verification failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
