<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/user-interactions', [App\Http\Controllers\UserInteractionController::class, 'store']);
