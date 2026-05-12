<?php

namespace App\Http\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AuthInterface
{
    public function register(array $data): array;

    public function login(string $email, string $password): array;

    public function refresh(string $refreshToken): array;

    public function logout(Request $user): void;

    public function resendOtp(string $email): JsonResponse;

    public function verifyOtp(string $email, string $otp): array;

    public function findUserByEmail(string $email): ?\App\Models\User;

    public function sendResetOtp(string $email): void;

    public function verifyResetOtp(string $email, string $otp): bool;

    public function resetPassword(string $email, string $otp, string $newPassword): void;
}
