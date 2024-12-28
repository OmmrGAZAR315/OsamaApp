<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|phone:INTERNATIONAL',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'fcm_token' => 'required'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $userData = $this->passUserData($user);
        $userData['subscription'] = $user->subscription;

        return $this->sendResponse($userData, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            DB::table('users')
            ->where('id', $user->id)
            ->update(['fcm_token' => $request->fcm_token ?? null ]);
            $userData = $this->passUserData($user);
            $userData['is_admin'] = $user->is_admin;
            $userData['subscription'] = $user->subscription;
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

        return $this->sendResponse($request->email,'User has been logged out successfully');
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
        $success['consultants_count'] =  $user->consultants()->count();
        return $success;
    }
}
