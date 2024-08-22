<?php
namespace App\Services;

use App\Exceptions\HabitNotFoundException;
use App\Models\User;
use App\Models\UserHabit;
use Illuminate\Support\Facades\Auth;

class HabitsService
{
    public function createHabit(array $request): UserHabit
    {
        return UserHabit::create([
            'user_id' => Auth::user()->id,
            'name' => $request['name'],
            'description' => $request['description'],
            'type' => $request['type'],
            'goal' => $request['goal'],
            'reward' => $request['reward'],
            'week_days_bitmask' => get_bitmask_from_days($request['week_days']),
            'default_unit_id' => $request['default_unit_id'] ?? null,
            'user_unit_id' => $request['user_unit_id'] ?? null,
            'due_to' => $request['due_to'] ?? null,
        ]);
    }

    /**
     * @throws HabitNotFoundException
     */
    public function updateHabit(array $request): array
    {
        $habit = UserHabit::where('id', $request['habit_id'])->where('user_id', Auth::user()->id)->first();
        if (!$habit) {
            throw new HabitNotFoundException("Habit not found");
        }

        return $this->extracted($habit, $request); // TODO: update habit log goal if it exists today
    }

    /**
     * @throws HabitNotFoundException
     */
    public function getUserHabits(): array
    {
        $userId = Auth::user()->id;
        $habits = UserHabit::where('user_id', $userId)->get();
        if (!$habits || count($habits) < 1) {
            throw new HabitNotFoundException("Habit with user_id {$userId} not found.");
        }

        return $habits->toArray();
    }

    /**
     * @param UserHabit $habit
     * @param array $request
     * @return array
     */
    public function extracted(UserHabit $habit, array $request): array
    {
        $habit->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'type' => $request['type'],
            'goal' => $request['goal'],
            'reward' => $request['reward'], // TODO: set 20 coins as a default reward.
            'week_days_bitmask' => get_bitmask_from_days($request['week_days']),
            'default_unit_id' => $request['default_unit_id'],
            'user_unit_id' => $request['user_unit_id'],
            'due_to' => $request['due_to'],
        ]);

        return $habit->toArray();
    }
}
