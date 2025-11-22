<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\PenaltyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Challenges
    Route::get('/challenges', [ChallengeController::class, 'index']);
    Route::get('/challenges/{id}', [ChallengeController::class, 'show']);
    Route::post('/challenges/{id}/join', [ChallengeController::class, 'join']);
    
    // Payments
    Route::get('/payments/user/{user_id}', [PaymentController::class, 'getUserPayments']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/challenge/{challenge_id}', [PaymentController::class, 'getChallengePayments']);
    
    // Materials
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::get('/materials/{id}', [MaterialController::class, 'show']);
    
    // Penalties
    Route::get('/penalties/user/{user_id}', [PenaltyController::class, 'getUserPenalties']);
    Route::post('/penalties/appeal', [PenaltyController::class, 'appeal']);
});

// Admin only routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/challenges', [ChallengeController::class, 'store']);
    Route::put('/challenges/{id}', [ChallengeController::class, 'update']);
    Route::delete('/challenges/{id}', [ChallengeController::class, 'destroy']);
    
    Route::post('/materials', [MaterialController::class, 'store']);
    
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
    Route::get('/stats/overview', [StatsController::class, 'overview']);
    Route::get('/stats/payments', [StatsController::class, 'paymentStats']);
    Route::get('/stats/challenges', [StatsController::class, 'challengeStats']);
});