<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InvalidCredentialsException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}
