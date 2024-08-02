<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Controllers\API\BaseController as BaseController;

class SocialController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'fcm_token' => 'required'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['provider'] = $request->provider ?? 'google';
        $user = User::create($input);
        if($user) {
            Auth::loginUsingId($user->id);
        }
        $userData = $this->passUserData($user);


        return $this->sendResponse($userData, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if($user) {
            $user->fcm_token = $request->fcm_token;
            Auth::loginUsingId($user->id);
            // DB::table('users')
            // ->where('id', $user->id)
            // ->update(['fcm_token' => $request->fcm_token ?? null ]);

            $userData = $this->passUserData($user);
            $userData['is_admin'] = $user->is_admin;
            return $this->sendResponse($userData, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
        }

    }
    public function logout(Request $request)
    {
        auth()->user()->fcm_token = null;
        auth()->user()->save();
        // revoke the token
        auth()->user()->currentAccessToken()->delete();

        return $this->sendResponse($request->email, 'User has been logged out successfully');
    }
    public function passUserData($user)
    {
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['id'] =  $user->id;
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;
        $success['phone'] =  $user->phone;
        $success['is_admin'] =  0;
        $success['created_at'] =  $user->created_at;
        $success['fcm_token'] =  $user->fcm_token;
        $success['provider'] =  $user->provider;
        return $success;
    }
}
