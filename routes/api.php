<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('clubs/{club}')->name('api.')->group(function () {
        Route::get('students', [ClubController::class, 'getStudents'])->name('clubs.students.index');
        Route::post('students', [ClubController::class, 'addStudent'])->name('clubs.students.add');
        Route::delete('students/{student}', [ClubController::class, 'removeStudent'])->name('clubs.students.remove');
        Route::put('students/{student}/role', [ClubController::class, 'updateStudentRole'])->name('clubs.students.role');
    });

    Route::get('students/{student}/clubs', [StudentController::class, 'getClubs'])->name('api.students.clubs');

    Route::apiResource('clubs', ClubController::class)->names('api.clubs');       // ← this
    Route::apiResource('students', StudentController::class)->names('api.students');
    Route::apiResource('events', EventController::class)->names('api.events');

    Route::post('clubs/{id}/restore', [ClubController::class, 'restore'])->name('api.clubs.restore');
    Route::post('students/{id}/restore', [StudentController::class, 'restore'])->name('api.students.restore');
    Route::post('events/{id}/restore', [EventController::class, 'restore'])->name('api.events.restore');
});
