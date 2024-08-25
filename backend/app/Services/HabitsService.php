<?php
namespace App\Services;

use App\Exceptions\HabitNotFoundException;
use App\Models\DefaultHabit;
use App\Models\User;
use App\Models\UserHabit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class HabitsService
{
    protected function getUser(): Authenticatable|User
    {
        return Auth::user();
    }

    public function createHabit(array $request): UserHabit
    {
        return UserHabit::create($this->prepareDataForHabit($request));
    }

    public function updateHabit(array $request): array
    {
        $user = $this->getUser();
        $habit = UserHabit::where('id', $request['habit_id'])->where('user_id', $user->id)->firstOrFail();

        $habit->update($this->prepareDataForHabit($request, $habit));
        $user->todayHabitLog($habit['id'])->update(['goal' => $habit['goal']]);

        return $habit->fresh()->toArray();
    }

    /**
     * @throws HabitNotFoundException
     */
    public function getUserHabits(): array
    {
        $userId = $this->getUser()->id;
        $habits = UserHabit::where('user_id', $userId)->get();

        if ($habits->isEmpty()) {
            throw new HabitNotFoundException("No habits found for user_id {$userId}.");
        }

        return $habits->toArray();
    }

    protected function prepareDataForHabit(array $request, UserHabit $habit = null): array
    {
        $user = $this->getUser();
        return [
            'user_id' => $user->id,
            'name' => $request['name'],
            'description' => $request['description'],
            'type' => $request['type'],
            'goal' => $request['goal'],
            'reward' => $this->calculateReward($request, $habit),
            'week_days_bitmask' => get_bitmask_from_days($request['week_days']),
            'default_unit_id' => $request['default_unit_id'] ?? $habit?->default_unit_id,
            'user_unit_id' => $request['user_unit_id'] ?? $habit?->user_unit_id,
            'due_to' => $request['due_to'] ?? $habit?->due_to,
        ];
    }

    protected function calculateReward(array $request, UserHabit $habit = null): int
    {
        $user = $this->getUser();
        $date = now($user->timezone)->format('Y-m-d');
        if ($user->subscription_end && $user->subscription_end >= $date) {
            return $request['reward'];
        }

        return $habit?->reward ?? DefaultHabit::DEFAULT_REWARD;
    }
}
