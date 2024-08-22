<?php

namespace App\Http\Controllers;

use App\Exceptions\HabitNotFoundException;
use App\Http\Requests\ClaimHabitRewardRequest;
use App\Http\Requests\HabitLogsRequest;
use App\Models\HabitLog;
use App\Services\HabitLogsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HabitLogsController extends Controller
{
    protected HabitLogsService $habitLogsService;

    public function __construct(HabitLogsService $habitLogsService)
    {
        $this->habitLogsService = $habitLogsService;
    }

    /**
     * @throws HabitNotFoundException
     */
    public function createHabitLog(HabitLogsRequest $request): JsonResponse
    {
        $habitLog = $this->habitLogsService->createHabitLog($request->validated());
        return response()->json(['message' => 'Create habit log', 'habit_log_id' => $habitLog->id]);
    }

    /**
     * @throws HabitNotFoundException
     */
    public function updateHabitLog(HabitLogsRequest $request): JsonResponse
    {
        $habitLog = $this->habitLogsService->updateHabitLog($request->validated());
        return response()->json(['message' => 'Update habit log', 'habit_log' => $habitLog]);
    }

    public function getHabitLogs(): JsonResponse
    {
        $habitLog = $this->habitLogsService->getHabitLogs();
        return response()->json(['message' => 'Get habit logs', 'habit_log' => $habitLog]);
    }


    /**
     * @throws HabitNotFoundException
     */
    public function claimHabitReward(ClaimHabitRewardRequest $request): JsonResponse
    {
        $habitLog = $this->habitLogsService->claimHabitReward($request['habit_log_id']);
        return response()->json(['message' => 'Update habit log', 'habit_log' => $habitLog]);
    }
}
