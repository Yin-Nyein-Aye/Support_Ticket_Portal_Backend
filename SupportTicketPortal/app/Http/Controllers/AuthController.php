<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Contracts\AuthInterface;
use App\Http\Resources\V1\AuthResource;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(
        private AuthInterface $authService
    ) {}

    // REGISTER send OTP to email
    public function store(Request $request)
    {
        try {
            $validated = $this->validateRegister($request);
            $this->authService->register($validated);

            return $this->successResponse(
                'Registration successful. Please verify your email.',
                [],
                201
            );
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->errors()['email'][0] ?? 'Validation error',
            ], 422);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    // VERIFY OTP (Auto login after verify)
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            /** @var array $result */
            $result = $this->authService->verifyOtp(
                $request->email,
                $request->otp
            );

            return $this->mapToAuthResource($result, $request);
        } catch (AuthenticationException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    // RESEND OTP
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $result = $this->authService->resendOtp($request->email);

        return response()->json($result);
    }

    // LOGIN
    public function login(Request $request)
    {
        $credentials = $this->validateLogin($request);

        try {

            $authPayload = $this->authService->login(
                $credentials['email'],
                $credentials['password']
            );
            return $this->mapToAuthResource($authPayload, $request);
        } catch (AuthenticationException $e) {
            return $this->errorResponse($e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', 500);
        }
    }

    // REFRESH TOKEN
    public function refresh(Request $request)
    {
        $data = $request->validate([
            'refresh_token' => 'required|string|size:128',
        ]);

        try {
            $result = $this->authService->refresh($data['refresh_token']);

            return $this->mapToAuthResource($result, $request);
        } catch (\Exception $e) {
            return $this->errorResponse('Invalid refresh token', 401);
        }
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    // VALIDATE LOGIN
    protected function validateLogin(Request $request): array
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);
    }

    // VALIDATE REGISTER
    protected function validateRegister(Request $request): array
    {
        return $request->validate([
            'organisation_id' => 'max:100',
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email',
            'password'    => [
                'required',
                'string',
                'min:6',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
        ], [
            'password.regex' => 'Password must contain at least one special character.',
        ]);
    }

    // Success Response Helpers
    protected function successResponse(string $message, $data = [], int $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    // Error Response Helpers
    protected function errorResponse(string $message, int $status = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $status);
    }

    // AuthController.php
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = $this->authService->findUserByEmail($request->email);
        if (!$user) {
            return $this->errorResponse('Email does not exist', 404);
        }

        // Send OTP for password reset
        $this->authService->sendResetOtp($user->email);

        return $this->successResponse('OTP sent to email');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        if (!$this->authService->verifyResetOtp($request->email, $request->otp)) {
            return $this->errorResponse('Invalid or expired OTP', 422);
        }

        return $this->successResponse('OTP verified');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        try {
            $this->authService->resetPassword($request->email, $request->otp, $request->password);
            return $this->successResponse('Password updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    // Helper to map auth data to AuthResource
    private function mapToAuthResource(array $data, Request $request): AuthResource
    {
        $user = $data['user'];
        $user->role = $user->getRoleNames()[0] ?? null;

        // Load organisation relation
        if (!$user->relationLoaded('organisation')) {
            $user->load('organisation');
        }

        $user->access_token  = $data['access_token'] ?? null;
        $user->refresh_token = $data['refresh_token'] ?? null;
        $user->token_type    = $data['token_type'] ?? 'Bearer';
        $user->expires_in    = $data['expires_in'] ?? 3600;

        return new AuthResource($user);
    }
}
