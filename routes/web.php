<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\ChallengeController as ApiChallengeController;
use App\Http\Controllers\Api\PaymentController as ApiPaymentController;
use App\Http\Controllers\Api\MaterialController as ApiMaterialController;
use App\Http\Controllers\Api\PenaltyController as ApiPenaltyController;
use App\Http\Controllers\Api\ChatbotController as ApiChatbotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public chatbot endpoints (use ApiChatbotController)
Route::get('/api/chatbot/suggestions', [ApiChatbotController::class, 'getSuggestions'])->name('chatbot.suggestions');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Language Routes
Route::post('/language/switch', [LanguageController::class, 'switchLanguage'])->name('language.switch');

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/feedback', [DashboardController::class, 'storeFeedback'])->name('dashboard.feedback.store');

    // Testimonials
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');

    // Materials
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');

    // Challenges
    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
    Route::get('/challenges/{challenge}', [ChallengeController::class, 'show'])->name('challenges.show');
    Route::post('/challenges/{challenge}/participate', [ChallengeController::class, 'participate'])->name('challenges.participate');
    Route::post('/challenges/{challengeId}/payment', [ChallengeController::class, 'makePayment'])->name('challenges.payment');

    // Groups
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

    // Direct Purchases
    Route::get('/direct-purchases', [MaterialController::class, 'directPurchases'])->name('direct_purchases.index');
    Route::get('/direct-purchases/{purchase}', [MaterialController::class, 'showDirectPurchase'])->name('direct_purchases.show');
    Route::post('/materials/{material}/direct-purchase', [MaterialController::class, 'directPurchase'])->name('materials.direct-purchase');

    // Lipa Kidogo
    Route::get('/lipa-kidogo', [MaterialController::class, 'lipaKidogo'])->name('lipa_kidogo.index');
    Route::get('/lipa-kidogo/{plan}', [MaterialController::class, 'showLipaKidogo'])->name('lipa_kidogo.show');
    Route::post('/materials/{material}/lipa-kidogo', [MaterialController::class, 'lipaKidogoPurchase'])->name('materials.lipa-kidogo');
    Route::post('/lipa-kidogo/{lipaKidogoId}/installment/{installmentId}/pay', [MaterialController::class, 'payLipaKidogoInstallment'])->name('lipa_kidogo.pay_installment');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

<<<<<<< HEAD
    // Chatbot API Routes (for authenticated users) - using ApiChatbotController
=======
    // Penalties (User can view their own penalties)
    Route::get('/penalties', [DashboardController::class, 'penalties'])->name('penalties.index');
    Route::get('/penalties/{penalty}', [DashboardController::class, 'showPenalty'])->name('penalties.show');
    Route::post('/penalties/{penalty}/appeal', [DashboardController::class, 'appealPenalty'])->name('penalties.appeal');

    // Chatbot API Routes (for authenticated users)
>>>>>>> 4e8a677 (chat system)
    Route::prefix('api/chatbot')->name('chatbot.')->group(function () {
        Route::post('/send', [ApiChatbotController::class, 'sendMessage'])->name('send');
        Route::get('/history', [ApiChatbotController::class, 'getHistory'])->name('history');
        Route::post('/rate', [ApiChatbotController::class, 'rateResponse'])->name('rate');
        Route::post('/clear', [ApiChatbotController::class, 'clearHistory'])->name('clear');
    });
});

// Admin Routes (Require Admin Authentication)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');

    // Material Management
    Route::get('/materials', [AdminController::class, 'materials'])->name('materials');
    Route::get('/materials/create', [AdminController::class, 'createMaterial'])->name('materials.create');
    Route::post('/materials', [AdminController::class, 'storeMaterial'])->name('materials.store');
    Route::get('/materials/{material}/edit', [AdminController::class, 'editMaterial'])->name('materials.edit');
    Route::put('/materials/{material}', [AdminController::class, 'updateMaterial'])->name('materials.update');
    Route::delete('/materials/{material}', [AdminController::class, 'destroyMaterial'])->name('materials.destroy');

    // Challenge Management
    Route::get('/challenges', [AdminController::class, 'challenges'])->name('challenges');
    Route::get('/challenges/create', [AdminController::class, 'createChallenge'])->name('challenges.create');
    Route::post('/challenges', [AdminController::class, 'storeChallenge'])->name('challenges.store');
    Route::get('/challenges/{challenge}/edit', [AdminController::class, 'editChallenge'])->name('challenges.edit');
    Route::put('/challenges/{challenge}', [AdminController::class, 'updateChallenge'])->name('challenges.update');
    Route::delete('/challenges/{challenge}', [AdminController::class, 'destroyChallenge'])->name('challenges.destroy');

    // Group Management
    Route::get('/groups', [AdminController::class, 'groups'])->name('groups');
    Route::get('/groups/create', [AdminController::class, 'createGroup'])->name('groups.create');
    Route::post('/groups', [AdminController::class, 'storeGroup'])->name('groups.store');
    Route::get('/groups/{id}/edit', [AdminController::class, 'editGroup'])->name('groups.edit');
    Route::put('/groups/{id}', [AdminController::class, 'updateGroup'])->name('groups.update');
    Route::delete('/groups/{id}', [AdminController::class, 'destroyGroup'])->name('groups.destroy');

    // Admin Direct Purchases and Lipa Kidogo Management
    Route::get('/direct-purchases', [AdminController::class, 'directPurchases'])->name('direct-purchases');
    Route::get('/lipa-kidogo-plans', [AdminController::class, 'lipaKidogoPlans'])->name('lipa-kidogo-plans');

    // Payment Management
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('payments.show');

    // Penalty Management
    Route::get('/penalties', [AdminController::class, 'penalties'])->name('penalties');
    Route::get('/penalties/create', [AdminController::class, 'createPenalty'])->name('penalties.create');
    Route::post('/penalties', [AdminController::class, 'storePenalty'])->name('penalties.store');
    Route::get('/penalties/{penalty}', [AdminController::class, 'showPenalty'])->name('penalties.show');
    Route::post('/penalties/{penalty}/resolve', [AdminController::class, 'resolvePenalty'])->name('penalties.resolve');

    // Notification Management
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{notification}', [AdminNotificationController::class, 'show'])->name('notifications.show');
    Route::get('/notifications/{notification}/edit', [AdminNotificationController::class, 'edit'])->name('notifications.edit');
    Route::put('/notifications/{notification}', [AdminNotificationController::class, 'update'])->name('notifications.update');
    Route::delete('/notifications/{notification}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');

    // Testimonial Management
    Route::get('/testimonials', [TestimonialController::class, 'adminIndex'])->name('testimonials');
    Route::post('/testimonials/{feedback}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('/testimonials/{feedback}/reject', [TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::post('/testimonials/{feedback}/feature', [TestimonialController::class, 'feature'])->name('testimonials.feature');
    Route::post('/testimonials/{feedback}/unfeature', [TestimonialController::class, 'unfeature'])->name('testimonials.unfeature');
    Route::delete('/testimonials/{feedback}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});


Route::post('/payments/lipa-kidogo/callback', [MaterialController::class, 'handleLipaKidogoCallback'])->name('lipa_kidogo.callback');

// Chatbot Routes
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage'])->name('chatbot.message');
