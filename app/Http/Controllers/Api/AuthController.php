<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->registerUser($request->validated());

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $result['access_token'],
            'token_type' => 'Bearer',
            'user' => $result['user'],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->loginUser($request->validated());
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], $e->status);
        }

        return response()->json([
            'message' => 'Logged in successfully',
            'access_token' => $result['access_token'],
            'token_type' => 'Bearer',
            'user' => $result['user'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logoutUser($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
