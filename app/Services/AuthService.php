<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function attemptLogin($credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = $this->userRepository->getUserByEmail($credentials['email']);
            $token = $user->createToken('API Token')->plainTextToken;

            return ['user' => $user, 'token' => $token];
        }

        return false;
    }

    public function logout($user)
    {
        $user->tokens()->delete();
    }
}
