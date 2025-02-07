<?php

use App\Models\User;
use Illuminate\Http\Request;

use App\Notifications\PushNewFCM;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\Payment\PaymobController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\ForgotPasswordController;

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});


Route::post('password/forgot', [ForgotPasswordController::class,'forgotPassword']);
Route::post('password/reset', [ResetPasswordController::class,'resetPassword']);


Route::controller(SocialController::class)->group(function () {
    Route::post('social/register', 'register');
    Route::post('social/login', 'login');
});



Route::controller(PaymobController::class)->prefix('paymob')->group(function () {
    Route::get('callback', 'callback');
    Route::post('transaction', 'handleTransaction');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('schedules', 'App\Http\Controllers\API\Schedule\ScheduleController@index');
    Route::get('schedules/{id}', 'App\Http\Controllers\API\Schedule\ScheduleController@show');
    Route::apiResource('slots', 'App\Http\Controllers\API\Schedule\SlotController');
    Route::apiResource('meetings', 'App\Http\Controllers\API\Meeting\MeetingController');
    Route::post('meetings/approve/{id}', 'App\Http\Controllers\API\Meeting\MeetingController@approveMeeting');
    Route::post('meetings/in-progress/{id}', 'App\Http\Controllers\API\Meeting\MeetingController@inProgressMeeting');
    Route::post('meetings/finish/{id}', 'App\Http\Controllers\API\Meeting\MeetingController@finishMeeting');

    Route::post('consultant/store', 'App\Http\Controllers\API\ConsultantController@store');
    Route::delete('consultant', 'App\Http\Controllers\API\ConsultantController@delete');
    Route::post('user/rtcToken', 'App\Http\Controllers\API\UserController@generateRtcToken');
    Route::get('myProfile', 'App\Http\Controllers\API\UserController@myProfile');
    Route::post('user/profile', 'App\Http\Controllers\API\UserController@update');
    Route::post('user/code', 'App\Http\Controllers\CodeController@store');
    Route::post('user/addUser', 'App\Http\Controllers\API\UserController@store');
    Route::post('quote', 'App\Http\Controllers\QuoteController@store');
    Route::get('quote', 'App\Http\Controllers\QuoteController@index');

    Route::controller(RegisterController::class)->group(function () {
        Route::post('logout', 'logout');
    });

    Route::post('/fcm/push', function () {
        $user = null;
        if(request()->has('email')) {
            $user = User::where('email', request()->email)->first();
        }

        if(request()->has('id')) {
            $user = User::find(request()->id);
        }

        if($user) {
            $user->notify(new PushNewFCM(request()->title, request()->body));
        }
    });
});
