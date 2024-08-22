<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class HabitNotFoundException extends Exception
{
    public function report()
    {
        //
    }

    public function render($request): JsonResponse
    {
        return response()->json(['error' => $this->getMessage()], 404);
    }
}
