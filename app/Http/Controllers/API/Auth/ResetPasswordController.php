<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\API\Auth\ResetPasswordRequest;

class ResetPasswordController extends BaseController
{
    private $otp;
    public function __construct(){
        $this->otp= new Otp();
    }
    public function resetPassword(ResetPasswordRequest $request){

        $otp2 = $this->otp->validate($request->email,$request->otp);
        if(! $otp2->status){
            return response()->json(['error'=>$otp2],401);

        }
        $user= User::where('email',$request->email)->first();
        $user->update(
            [
                'password'=> Hash::make($request->password)
            ]
        );
        $user->tokens()->delete();
        $success['succees']=true;
        return response()->json($success,200);
    }
}
