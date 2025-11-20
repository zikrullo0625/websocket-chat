<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\DTOs\LoginDTO;
use App\Modules\User\DTOs\RegisterDTO;
use App\Modules\User\Requests\LoginRequest;
use App\Modules\User\Requests\RegisterRequest;
use App\Modules\User\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    public function __construct(
     public AuthService $authService
    )
    {}
    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register(RegisterDTO::fromRequest($request));

        return response()->json([
            'success' => true,
            'token' => $data['token'],
            'user' => $data['user']
        ]);
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login(LoginDTO::fromRequest($request));

        return response()->json([
            'success' => true,
            'token' => $data['token'],
            'user' => $data['user']
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => Auth::user()
        ]);
    }
}
