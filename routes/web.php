<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Notifications\AccountActivated;

Route::get('/', function () {
    return view('welcome');
});
