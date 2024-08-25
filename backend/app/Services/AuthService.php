<?php
namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'timezone' => $data['timezone']
        ]);
    }

    /**
     * @throws ValidationException
     * @throws InvalidCredentialsException
     */
    public function loginUser(array $request): array
    {
        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update([
                'timezone' => request('timezone'),
            ]);
            $token = $user->createToken('API Token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        }

        throw new InvalidCredentialsException();
    }
}
