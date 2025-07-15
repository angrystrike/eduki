<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function registerUser(array $data): array;
    public function loginUser(array $credentials): array;
    public function logoutUser(User $user): void;
}
