<?php

use App\Models\User;
use Illuminate\Http\Request;

use App\Notifications\PushNewFCM;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\Payment\PaymobController;
use App\Http\Controllers\API\SocialController;

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

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

    Route::controller(RegisterController::class)->group(function () {
        Route::post('logout', 'logout');
    });

    Route::post('/fcm/push', function () {
        $user = User::where('email', request()->email)->first();
        $user->notify(new PushNewFCM(request()->title, request()->body));
    });
});
