<?php
namespace App\Services;

use App\Exceptions\HabitNotFoundException;
use App\Models\HabitLog;
use App\Models\User;
use App\Models\UserHabit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class HabitLogsService
{
    /**
     * @throws HabitNotFoundException
     */
    public function createHabitLog(array $request): HabitLog
    {
        $user = Auth::user();
        $habit = UserHabit::where('id', $request['habit_id'])->where('user_id', $user->id)->first();
        if (!$habit) {
            throw new HabitNotFoundException("Habit not found");
        }
        $currentDateTime = Carbon::now($user->timezone);
        $date = $currentDateTime->format('Y-m-d');
        $time = $currentDateTime->format('H:i');

        $habitLog = $user->habitLogs()->where('habit_id', $habit->id)->where('date', $date)->first();

        if ($habitLog) {
            throw new HabitNotFoundException("Habit log already exists for today");
        }

        return HabitLog::create([
            'user_id' => $user->id,
            'habit_id' => $request['habit_id'],
            'unit_progress' => $request['unit_progress'],
            'goal' => $habit->goal,
            'date' => $date,
            'time' => $time,
            'claimed_reward' => 0,
            'notes' => $request['notes'] ?? null,
            'skipped' => $request['skipped'] ?? false,
        ]);
    }

    /**
     * @throws HabitNotFoundException
     */
    public function updateHabitLog(array $request): array
    {
        $user = Auth::user();
        $habitLog = $user->habitLogs()->where('id', $request['id'])->first();

        if (!$habitLog) {
            throw new HabitNotFoundException("Habit log not found");
        }
        $createdDate = Carbon::createFromFormat('Y-m-d', $habitLog->date, $user->timezone);

        if (!$createdDate->isToday()) {
            throw new HabitNotFoundException("Habit log can only be updated on the day it was created."); // TODO: add custom exception
        }

        $currentDateTime = Carbon::now($user->timezone);
        $time = $currentDateTime->format('H:i');

        $habitLog->update([
            'unit_progress' => $request['unit_progress'],
            'time' => $time,
            'notes' => $request['notes'] ?? null,
            'skipped' => $request['skipped'] ?? false,
        ]);

        return $habitLog->toArray();
    }

    /**
     * @throws HabitNotFoundException
     */
    public function getHabitLogs(): array
    {
        $userId = Auth::user()->id;
        $habits = HabitLog::where('user_id', $userId)->get();
        if (!$habits || count($habits) < 1) {
            throw new HabitNotFoundException("Habit logs not found.");
        }

        return $habits->toArray();
    }


    /**
     * @throws HabitNotFoundException
     */
    public function claimHabitReward(int $habitLogId): array
    {
        $user = Auth::user();
        $habitLog = HabitLog::where('id', $habitLogId)->where('user_id', $user->id)->first();
        if (!$habitLog) {
            throw new HabitNotFoundException("Habit log not found.");
        }
        if ($habitLog->claimed_reward > 0) {
            throw new HabitNotFoundException("Reward already claimed."); //TODO: Add custom exception
        }
        if (!$habitLog->is_completed) {
            throw new HabitNotFoundException("Habit is not complete."); //TODO: Add custom exception
        }
        $habit = $habitLog->habit()->first();
        $habitLog->claimed_reward = $habit->reward;
        $habitLog->save();

        $user->rewa_coins += $habit->reward;
        $user->save();

        return $habitLog->toArray();
    }
}
