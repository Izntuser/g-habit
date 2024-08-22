<?php

namespace Database\Seeders;

use App\Models\DefaultHabit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultHabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weekDaysBitmask = get_all_week_days_bitmask();
        DefaultHabit::truncate();
        DefaultHabit::insert([
            ['id' => 1, 'name' => 'Drink water', 'description' => 'Drink 8 cups of water', 'default_unit_id' => 4, 'week_days_bitmask' => $weekDaysBitmask, 'goal' => 8, 'reward' => 20, 'type' => DefaultHabit::TYPE_BUILDING, 'visible' => true],
            ['id' => 2, 'name' => 'Walk', 'description' => 'Do 10,000 steps today', 'default_unit_id' => 7, 'week_days_bitmask' => $weekDaysBitmask, 'goal' => 10000, 'reward' => 20, 'type' => DefaultHabit::TYPE_BUILDING, 'visible' => true],
            ['id' => 3, 'name' => 'Read', 'description' => 'Read a book 20 minutes', 'default_unit_id' => 2, 'week_days_bitmask' => $weekDaysBitmask, 'goal' => 20, 'reward' => 20, 'type' => DefaultHabit::TYPE_BUILDING, 'visible' => true],
            ['id' => 4, 'name' => 'No smoking', 'description' => 'Say no to smoking', 'default_unit_id' => 1, 'week_days_bitmask' => $weekDaysBitmask, 'goal' => 1, 'reward' => 20, 'type' => DefaultHabit::TYPE_QUITTING, 'visible' => true],
            ['id' => 5, 'name' => 'No alcohol', 'description' => 'Say no to alcohol', 'default_unit_id' => 1, 'week_days_bitmask' => $weekDaysBitmask, 'goal' => 1, 'reward' => 20, 'type' => DefaultHabit::TYPE_QUITTING, 'visible' => true],
            ['id' => 6, 'name' => 'No fast food', 'description' => 'No more yami?', 'default_unit_id' => 1, 'week_days_bitmask' => $weekDaysBitmask, 'goal' => 1, 'reward' => 20, 'type' => DefaultHabit::TYPE_QUITTING, 'visible' => true],
            ['id' => 7, 'name' => 'No soda', 'description' => 'No coca', 'default_unit_id' => 1, 'week_days_bitmask' => $weekDaysBitmask, 'goal' => 1, 'reward' => 20, 'type' => DefaultHabit::TYPE_QUITTING, 'visible' => true],
        ]);
    }
}
