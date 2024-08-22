<?php

const WEEK_DAYS_BITMASK = [
    1 => 'Monday',
    2 => 'Tuesday',
    4 => 'Wednesday',
    8 => 'Thursday',
    16 => 'Friday',
    32 => 'Saturday',
    64 => 'Sunday'
];
const WEEK_DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
const ALL_WEEK_DAYS = 127;


if (!function_exists('get_week_days')) {
    function get_week_days(): array
    {
        return WEEK_DAYS;
    }
}

if (!function_exists('get_all_week_days_bitmask')) {
    function get_all_week_days_bitmask(): int
    {
        return ALL_WEEK_DAYS;
    }
}

if (!function_exists('get_week_days_bitmask')) {
    function get_week_days_bitmask(): array
    {
        return WEEK_DAYS_BITMASK;
    }
}

if (!function_exists('get_days_from_bitmask')) {
    function get_days_from_bitmask($bitmask): array
    {
        $selectedDays = [];

        foreach (WEEK_DAYS_BITMASK as $bit => $day) {
            if ($bitmask & $bit) {
                $selectedDays[] = $day;
            }
        }

        return $selectedDays;
    }
}


if (!function_exists('get_bitmask_from_days')) {
    function get_bitmask_from_days($weekDays): int
    {
        $bitmask = 0;

        foreach (WEEK_DAYS_BITMASK as $bit => $day) {
            foreach ($weekDays as $selectedDay) {
                if ($selectedDay === $day) {
                    $bitmask += $bit;
                }
            }
        }

        return $bitmask;
    }
}
