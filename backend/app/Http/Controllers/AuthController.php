<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->authService->registerUser($request->validated());

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    /**
     * @throws InvalidCredentialsException
     * @throws ValidationException
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $authData = $this->authService->loginUser($request->validated());

        return response()->json([
            'message' => 'Login successful',
            'user' => $authData['user'],
            'token' => $authData['token']
        ]);
    }
}
