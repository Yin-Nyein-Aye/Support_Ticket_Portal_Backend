<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'store']);
Route::post('/verify', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/verify-otp', [AuthController::class, 'verifyResetOtp']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);
Route::apiResource('organisations', OrganisationController::class)->only(['index']);

// Route::post('/refresh', [AuthController::class, 'refresh']);
Route::middleware(['auth:sanctum', 'role:agent'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('organisations', OrganisationController::class)->only(['store', 'update', 'destroy']);
    Route::get('/agents', [UserController::class, 'agents']);
    Route::put('/tickets/{id}/assign', [TicketController::class, 'assign']);
    Route::post('roles/{id}/permissions', [RoleController::class, 'assignPermissions']);
    Route::put('users/{id}/assign-role', [UserController::class, 'assignRole']);
});

Route::middleware(['auth:sanctum', 'role:client|agent'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('tickets', TicketController::class);
    Route::put('/tickets/{id}/client-update', [TicketController::class, 'clientUpdate'])->name('tickets.client.update');
    Route::get('/tickets/{ticket_id}/comments', [CommentController::class, 'index']);
    Route::post('/tickets/{ticket_id}/comments', [CommentController::class, 'store']);
});
