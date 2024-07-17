<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('schedules', 'App\Http\Controllers\API\Schedule\ScheduleController@getSchedules');
    Route::apiResource('slots', 'App\Http\Controllers\API\Schedule\SlotController');
});
