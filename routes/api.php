<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EventController;

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Club-Student relationship routes
    Route::prefix('clubs/{club}')->group(function () {
        Route::get('students', [ClubController::class, 'getStudents']);
        Route::post('students', [ClubController::class, 'addStudent']);
        Route::delete('students/{student}', [ClubController::class, 'removeStudent']);
        Route::put('students/{student}/role', [ClubController::class, 'updateStudentRole']);
    });

    // Student-Club relationship route
    Route::get('students/{student}/clubs', [StudentController::class, 'getClubs']);

    // Resource routes
    Route::apiResource('clubs', ClubController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('events', EventController::class);

    // Restore routes for soft-deleted resources
    Route::post('clubs/{id}/restore', [ClubController::class, 'restore']);
    Route::post('students/{id}/restore', [StudentController::class, 'restore']);
    Route::post('events/{id}/restore', [EventController::class, 'restore']);
});