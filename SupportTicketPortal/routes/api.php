<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'store']);
Route::post('/verify', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/verify-otp', [AuthController::class, 'verifyResetOtp']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);
Route::apiResource('organisations', OrganisationController::class)->only(['index']);

Route::middleware(['auth:sanctum', 'role:agent'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::post('roles/{id}/permissions', [RoleController::class, 'assignPermissions']);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('users', UserController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('/agents', [UserController::class, 'agents']);
    Route::put('users/{id}/assign-role', [UserController::class, 'assignRole']);
    Route::apiResource('organisations', OrganisationController::class)->except(['index']);
    Route::apiResource('tickets', TicketController::class)->only(['update']);
    Route::put('/tickets/{id}/assign', [TicketController::class, 'assign']);
});

Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
    Route::apiResource('tickets', TicketController::class)->only(['store']);
    Route::put('/tickets/{id}/client-update', [TicketController::class, 'clientUpdate'])->name('tickets.client.update');
});

Route::middleware(['auth:sanctum', 'role:client|agent'])->group(function () {
    Route::apiResource('tickets', TicketController::class)->only(['index', 'show',
        'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/tickets/{ticket_id}/comments', CommentController::class);
    // Route::get('/tickets/{ticket_id}/comments', [CommentController::class, 'index']);
    // Route::post('/tickets/{ticket_id}/comments', [CommentController::class, 'store']);
});
