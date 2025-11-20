<?php

namespace App\Modules\User\Services;

use App\Modules\User\DTOs\LoginDTO;
use App\Modules\User\DTOs\RegisterDTO;
use App\Modules\User\Repositories\UserRepository;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private readonly UserRepository $repo
    )
    {}

    public function register(RegisterDTO $dto)
    {
        $user = $this->repo->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        Auth::login($user);

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function login(LoginDTO $dto)
    {
        if (!Auth::attempt($dto->toArray())) {
            throw new DomainException('Invalid email or password');
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }
}
