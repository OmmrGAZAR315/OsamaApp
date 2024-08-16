<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use App\Notifications\ResetPasswordNotification;
use App\Http\Requests\API\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends BaseController
{
    
    public function forgotPassword(ForgotPasswordRequest $request){
        $input= $request->only('email');
        $user= User::where('email',$input)->first();
        if(!$user){
           return $this->sendError('User not found',[]);
        }
        $user->notify(new ResetPasswordNotification());
        $success['success'] = true;
        return response()->json($success,200);
    }
}
