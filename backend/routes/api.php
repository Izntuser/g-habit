<?php

use App\Http\Controllers\HabitLogsController;
use App\Http\Controllers\HabitsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/habits-default', [HabitsController::class, 'getDefaultHabits']);
    Route::get('/habits', [HabitsController::class, 'getUserHabits']);
    Route::put('/habit-create', [HabitsController::class, 'createUserHabit']);
    Route::post('/habit-update', [HabitsController::class, 'updateUserHabit']);

    Route::get('/habit-logs', [HabitLogsController::class, 'getHabitLogs']);
    Route::put('/habit-log-create', [HabitLogsController::class, 'createHabitLog']);
    Route::post('/habit-log-update', [HabitLogsController::class, 'updateHabitLog']);
    Route::post('/habit-log-claim-reward', [HabitLogsController::class, 'claimHabitReward']);

    Route::get('/user', function () {
        return response()->json(Auth::user());
    });
});
