<?php

namespace App\Http\Controllers;

use App\Exceptions\HabitNotFoundException;
use App\Http\Requests\HabitRequest;
use App\Models\DefaultHabit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\HabitsService;
use Illuminate\Validation\ValidationException;

class HabitsController extends Controller
{
    protected HabitsService $habitsService;

    public function __construct(HabitsService $habitsService)
    {
        $this->habitsService = $habitsService;
    }

    public function createUserHabit(HabitRequest $request): JsonResponse
    {
        $habit = $this->habitsService->createHabit($request->validated());
        return response()->json(['message' => 'Create user habit', 'habit_id' => $habit->id]);
    }

    /**
     */
    public function updateUserHabit(HabitRequest $request): JsonResponse
    {
        $habit = $this->habitsService->updateHabit($request->validated());
        return response()->json(['message' => 'Update user habit', 'habit' => $habit]);
    }

    /**
     * @throws HabitNotFoundException
     */
    public function getUserHabits(): JsonResponse
    {
        $habits = $this->habitsService->getUserHabits();
        return response()->json(['message' => 'User habits', 'habits' => $habits]);
    }

    public function getDefaultHabits(): JsonResponse
    {
        $habits = DefaultHabit::get();
        return response()->json(['message' => 'Default habits', 'habits' => $habits]);
    }
}
