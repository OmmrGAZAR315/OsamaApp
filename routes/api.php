<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\Payment\PaymobController;

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::controller(PaymobController::class)->prefix('paymob')->group(function () {
    Route::get('callback','callback');
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
});
