<?php

namespace App\Http\Services;

use App\Http\Contracts\AuthInterface;
use App\Http\Repositories\AuthRepository;
use App\Jobs\SendEmailVerificationJob;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class AuthService implements AuthInterface
{
    public function __construct(
        private AuthRepository $authRepository
    ) {}

    public function register(array $data): array
    {
        // Check if email already exists
        $existingUser = $this->authRepository->findByEmail($data['email']);
        if ($existingUser) {
            throw new \Exception('Email is already registered.');
        }
        // Create avatar initials
        $avatar = strtoupper(substr($data['first_name'], 0, 1).substr($data['last_name'], 0, 1));

        // Create user
        $user = $this->authRepository->createUser([
            'organisation_id' => $data['organisation_id'] ?? null,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'avatar_initials' => $avatar,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
            'is_confirm' => false,
        ]);

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in Redis (DB 1, expires in 5 minutes)
        Cache::store('redis')->put('otp_'.$user->email, $otp, 300);
        // Dispatch email verification job
        SendEmailVerificationJob::dispatch($user->email, $otp);

        return [
            'user' => $user,
            'message' => 'OTP sent to email',
        ];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->authRepository->findByEmail($email);

        // Handle invalid credentials
        if (! $user || ! Hash::check($password, $user->password)) {
            throw new AuthenticationException('Invalid email or password.');
        }

        // Handle unverified email
        if (! $user->hasVerifiedEmail()) {
            throw new AuthenticationException('Please verify your email before logging in.');
        }

        // Handle inactive users
        if (! $user->is_active) {
            throw new AuthenticationException('Your account is inactive. Contact admin.');
        }

        return $this->generateAuthPayload($user);
    }

    public function verifyOtp(string $email, string $otp): array
    {
        // Get OTP from Redis
        $cachedOtp = Cache::store('redis')->get('otp_'.$email);

        if (! $cachedOtp || $cachedOtp != $otp) {
            throw new AuthenticationException('Invalid or expired OTP');
        }

        // Remove OTP after success
        Cache::store('redis')->forget('otp:'.$email);

        // Get user
        $user = $this->authRepository->findByEmail($email);

        if (! $user) {
            throw new \Exception('User not found.');
        }

        // Mark as verified
        $user->email_verified_at = now();
        $user->is_confirm = true;
        $user->save();

        // Auto login after verification
        return $this->generateAuthPayload($user);
    }

    public function resendOtp(string $email): JsonResponse
    {
        // Check if email exists in DB
        if (! User::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not exist',
            ], 404);
        }

        $attemptKey = 'otp_attempt_'.$email;

        // Get current attempts
        $attempts = Redis::get($attemptKey) ?? 0;

        // If exceeded max attempts
        if ($attempts >= 3) {
            $ttl = Redis::ttl($attemptKey);
            $minutes = floor($ttl / 60);
            $seconds = $ttl % 60;

            return response()->json([
                'success' => false,
                'message' => 'Too many OTP requests. Try again later.',
                'retry_after' => [
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                ],
            ], 429);
        }

        // Increase attempt count (10 min window)
        Redis::setex($attemptKey, 10 * 60, $attempts + 1);

        // Generate OTP
        $otp = rand(100000, 999999);
        Redis::setex('otp_'.$email, 5 * 60, $otp); // 5 minutes

        // Dispatch email
        SendEmailVerificationJob::dispatch($email, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
        ]);
    }

    public function refresh(string $refreshToken): array
    {
        $user = $this->authRepository->findByRefreshToken($refreshToken);

        if (! $user) {
            $this->throwAuthException('Invalid refresh token');
        }

        return $this->generateAuthPayload($user, true);
    }

    public function logout(Request $request): void
    {
        $user = $request->user();

        $user->currentAccessToken()?->delete();
        $this->authRepository->updateRefreshToken($user, null);
    }

    // Helper to generate auth payload with optional refresh token rotation
    private function generateAuthPayload(User $user, bool $rotateRefreshToken = true): array
    {
        $accessToken = $user->createToken('access_token')->plainTextToken;

        $refreshToken = $rotateRefreshToken
            ? $this->rotateRefreshToken($user)
            : $user->refresh_token;

        return [
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ];
    }

    // Helper to rotate refresh token
    private function rotateRefreshToken(User $user): string
    {
        $refreshToken = Str::random(128);
        $this->authRepository->updateRefreshToken($user, $refreshToken);

        return $refreshToken;
    }

    // Helper to throw authentication exceptions
    private function throwAuthException(string $message): void
    {
        throw new AuthenticationException($message);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->authRepository->findByEmail($email);
    }

    public function sendResetOtp(string $email): void
    {
        $otp = rand(100000, 999999);
        Cache::store('redis')->put('reset_otp_'.$email, $otp, 300); // 5 mins
        SendEmailVerificationJob::dispatch($email, $otp); // reuse existing job
    }

    public function verifyResetOtp(string $email, string $otp): bool
    {
        $cachedOtp = Cache::store('redis')->get('reset_otp_'.$email);

        return $cachedOtp && $cachedOtp == $otp;
    }

    public function resetPassword(string $email, string $otp, string $newPassword): void
    {
        if (! $this->verifyResetOtp($email, $otp)) {
            throw new \Exception('Invalid or expired OTP');
        }

        $user = $this->authRepository->findByEmail($email);
        if (! $user) {
            throw new \Exception('User not found');
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        // Remove OTP after successful reset
        Cache::store('redis')->forget('reset_otp_'.$email);
    }
}
