<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class RewardAlreadyClaimedException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(['error' => 'Reward already claimed.'], 409);
    }
}
