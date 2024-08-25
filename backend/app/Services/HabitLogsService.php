<?php
namespace App\Services;

use App\Exceptions\ForbiddenException;
use App\Exceptions\HabitIsNotCompleteException;
use App\Exceptions\HabitLogExistsException;
use App\Exceptions\HabitNotFoundException;
use App\Exceptions\RewardAlreadyClaimedException;
use App\Models\HabitLog;
use App\Models\User;
use App\Models\UserHabit;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class HabitLogsService
{
    protected function getUser(): Authenticatable|User
    {
        return Auth::user();
    }

    protected function getHabit(int $habitId, int $userId)
    {
        return UserHabit::where('id', $habitId)->where('user_id', $userId)->firstOrFail();
    }

    /**
     * @throws HabitLogExistsException
     */
    public function createHabitLog(array $request): HabitLog
    {
        $user = $this->getUser();
        $habit = $this->getHabit($request['habit_id'], $user->id);
        $date = Carbon::now($user->timezone)->format('Y-m-d');

        $this->checkForExistingHabitLog($habit->id, $date);

        return HabitLog::create($this->prepareLogData($request, $habit, $date));
    }

    protected function prepareLogData(array $request, $habit, $date): array
    {
        $time = Carbon::now($habit->user->timezone)->format('H:i');
        return [
            'user_id' => $habit->user_id,
            'habit_id' => $habit->id,
            'unit_progress' => $request['unit_progress'],
            'goal' => $habit->goal,
            'date' => $date,
            'time' => $time,
            'claimed_reward' => 0,
            'notes' => $request['notes'] ?? null,
            'skipped' => $request['skipped'] ?? false,
        ];
    }

    /**
     * @throws HabitLogExistsException
     */
    protected function checkForExistingHabitLog(int $habitId, string $date): void
    {
        if (HabitLog::where('habit_id', $habitId)->where('date', $date)->exists()) {
            throw new HabitLogExistsException("Habit log already exists for today");
        }
    }

    /**
     * @throws ForbiddenException
     */
    public function updateHabitLog(array $request): array
    {
        $user = $this->getUser();
        $habitLog = $user->habitLogs()->where('id', $request['id'])->firstOrFail();
        $this->ensureLogIsForToday($habitLog);

        $habitLog->update($this->prepareUpdateLogData($request));

        return $habitLog->toArray();
    }

    /**
     * @throws ForbiddenException
     */
    protected function ensureLogIsForToday(HabitLog $habitLog): void
    {
        $createdDate = Carbon::createFromFormat('Y-m-d', $habitLog->date, $habitLog->user->timezone);
        if (!$createdDate->isToday()) {
            throw new ForbiddenException("Habit log can only be updated on the day it was created.");
        }
    }

    protected function prepareUpdateLogData(array $request): array
    {
        $time = Carbon::now()->format('H:i');
        return [
            'unit_progress' => $request['unit_progress'],
            'time' => $time,
            'notes' => $request['notes'] ?? null,
            'skipped' => $request['skipped'] ?? false,
        ];
    }

    /**
     * @throws HabitNotFoundException
     */
    public function getHabitLogs(): array
    {
        $user = $this->getUser();
        $habitLogs = $user->habitLogs;
        if ($habitLogs->isEmpty()) {
            throw new HabitNotFoundException("Habit logs not found.");
        }

        return $habitLogs->toArray();
    }

    /**
     * @throws HabitIsNotCompleteException
     * @throws RewardAlreadyClaimedException
     */
    public function claimHabitReward(int $habitLogId): array
    {
        $habitLog = $this->getUser()->habitLogs()->where('id', $habitLogId)->firstOrFail();
        $this->checkRewardStatus($habitLog);

        $habitLog->update(['claimed_reward' => $habitLog->habit->reward]);
        $this->getUser()->increment('rewa_coins', $habitLog->habit->reward);

        return $habitLog->toArray();
    }

    /**
     * @throws RewardAlreadyClaimedException
     * @throws HabitIsNotCompleteException
     */
    protected function checkRewardStatus(HabitLog $habitLog): void
    {
        if ($habitLog->claimed_reward > 0) {
            throw new RewardAlreadyClaimedException("Reward already claimed.");
        }
        if (!$habitLog->is_completed) {
            throw new HabitIsNotCompleteException("Habit is not complete.");
        }
    }
}
