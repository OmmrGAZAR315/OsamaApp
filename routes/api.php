<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('schedules', 'App\Http\Controllers\API\Schedule\ScheduleController@index');
    Route::get('schedules/{id}', 'App\Http\Controllers\API\Schedule\ScheduleController@show');
    Route::apiResource('slots', 'App\Http\Controllers\API\Schedule\SlotController');
    Route::apiResource('meetings', 'App\Http\Controllers\API\Meeting\MeetingController');

});
