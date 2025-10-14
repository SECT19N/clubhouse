<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EventController;

Route::apiResource('clubs', ClubController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('events', EventController::class);