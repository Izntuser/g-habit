<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ForbiddenException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(['error' => $this->getMessage()], 403);
    }
}
