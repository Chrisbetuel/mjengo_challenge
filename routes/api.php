<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\ChallengeController as ApiChallengeController;
use App\Http\Controllers\Api\PaymentController as ApiPaymentController;
use App\Http\Controllers\Api\MaterialController as ApiMaterialController;
use App\Http\Controllers\Api\PenaltyController as ApiPenaltyController;
use App\Http\Controllers\Api\ChatbotController as ApiChatbotController;
use App\Http\Controllers\Api\AdminController as ApiAdminController;
use App\Http\Controllers\Api\GroupController as ApiGroupController; // Create this controller for mobile admin

// Public Mobile Auth Routes (No auth required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/forgot-password/otp', [AuthController::class, 'sendOtp']);
    Route::post('/reset-password/otp', [AuthController::class, 'resetPasswordWithOtp']);
    Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Protected Mobile Routes (Require Sanctum Token)
Route::middleware('auth:sanctum')->group(function () {
    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Challenges (Mobile)
    Route::get('/challenges', [ApiChallengeController::class, 'index']);
    Route::get('/challenges/{id}', [ApiChallengeController::class, 'show']);
    Route::post('/challenges/{id}/join', [ApiChallengeController::class, 'join']);

    Route::get('/groups', [ApiGroupController::class, 'index']);
    Route::post('/groups', [ApiGroupController::class, 'store']);
    Route::get('/groups/my', [ApiGroupController::class, 'myGroups']);

    Route::get('/lipa-kidogo', [PaymentController::class, 'getUserLipaKidogo']);
    Route::get('/lipa-kidogo/{plan_id}/installments', [PaymentController::class, 'getInstallments']);
    Route::post('/lipa-kidogo/installments/{id}/pay', [PaymentController::class, 'payInstallment']);

    
    // Payments (Mobile)
    Route::get('/payments/user/{user_id}', [ApiPaymentController::class, 'getUserPayments']);
    Route::post('/payments', [ApiPaymentController::class, 'store']);
    Route::get('/payments/challenge/{challenge_id}', [ApiPaymentController::class, 'getChallengePayments']);

    // Materials (Mobile)
    Route::get('/materials', [ApiMaterialController::class, 'index']);
    Route::get('/materials/{id}', [ApiMaterialController::class, 'show']);

    // Penalties (Mobile)
    Route::get('/penalties/user/{user_id}', [ApiPenaltyController::class, 'getUserPenalties']);
    Route::post('/penalties/appeal', [ApiPenaltyController::class, 'appeal']);

    // Chatbot (Mobile)
    Route::prefix('chatbot')->group(function () {
        Route::post('/send', [ApiChatbotController::class, 'sendMessage']);
        Route::get('/history', [ApiChatbotController::class, 'getHistory']);
        Route::post('/rate', [ApiChatbotController::class, 'rateResponse']);
        Route::post('/clear', [ApiChatbotController::class, 'clearHistory']);
        Route::get('/suggestions', [ApiChatbotController::class, 'getSuggestions']);
    });
});

// Mobile Admin Routes (Require Sanctum Token + Admin Middleware)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Challenges (Admin)
    Route::post('/challenges', [ApiChallengeController::class, 'store']);
    Route::put('/challenges/{id}', [ApiChallengeController::class, 'update']);
    Route::delete('/challenges/{id}', [ApiChallengeController::class, 'destroy']);

    // Materials (Admin)
    Route::post('/materials', [ApiMaterialController::class, 'store']);

    // Add more admin endpoints as needed (e.g., users, stats)
    // Example:
    // Route::get('/users', [ApiAdminController::class, 'users']);
    // Route::get('/stats/overview', [ApiAdminController::class, 'overview']);
});