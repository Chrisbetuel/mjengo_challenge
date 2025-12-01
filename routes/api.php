<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\PenaltyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

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

// Chatbot routes (accessible from web with auth)
Route::middleware('auth')->prefix('chatbot')->name('chatbot')->group(function () {
    Route::post('/send', [ChatbotController::class, 'sendMessage'])->name('send');
    Route::get('/history', [ChatbotController::class, 'getHistory'])->name('history');
    Route::post('/rate', [ChatbotController::class, 'rateResponse'])->name('rate');
    Route::post('/clear', [ChatbotController::class, 'clearHistory'])->name('clear');
    Route::get('/suggestions', [ChatbotController::class, 'getSuggestions'])->name('suggestions');
});

// Admin only routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/challenges', [ChallengeController::class, 'store']);
    Route::put('/challenges/{id}', [ChallengeController::class, 'update']);
    Route::delete('/challenges/{id}', [ChallengeController::class, 'destroy']);
    
    Route::post('/materials', [MaterialController::class, 'store']);
    
    // Uncomment these if UserController and StatsController are created
    // Route::get('/users', [UserController::class, 'index']);
    // Route::get('/users/{id}', [UserController::class, 'show']);
    // Route::put('/users/{id}', [UserController::class, 'update']);
    // Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
    // Route::get('/stats/overview', [StatsController::class, 'overview']);
    // Route::get('/stats/payments', [StatsController::class, 'paymentStats']);
    // Route::get('/stats/challenges', [StatsController::class, 'challengeStats']);
});
