<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\WebClubController;
use App\Http\Controllers\Web\WebEventController;
use App\Http\Controllers\Web\WebStudentController;
use Illuminate\Support\Facades\Route;

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

// Auth-required routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Clubs
    Route::resource('clubs', WebClubController::class);
    Route::post('clubs/{club}/students', [WebClubController::class, 'addStudent'])->name('clubs.students.add');
    Route::delete('clubs/{club}/students/{student}', [WebClubController::class, 'removeStudent'])->name('clubs.students.remove');
    Route::patch('clubs/{club}/students/{student}/role', [WebClubController::class, 'updateStudentRole'])->name('clubs.students.role');

    // Students
    Route::get('students/export', [WebStudentController::class, 'export'])->name('students.export');
    Route::resource('students', WebStudentController::class);

    // Events
    Route::resource('events', WebEventController::class);
});
