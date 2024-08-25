<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class HabitIsNotCompleteException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(['error' => 'Habit is not complete.'], 409);
    }
}
