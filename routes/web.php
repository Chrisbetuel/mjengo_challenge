<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);

    // Password reset routes
    Route::get('/forgot-password', [ResetPasswordController::class, 'showForgotPassword'])->name('password.forgot');
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Language switching route
Route::post('/lang/switch', [LanguageController::class, 'switchLanguage'])->name('lang.switch');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/feedback', [DashboardController::class, 'storeFeedback'])->name('dashboard.feedback.store');

    // Challenges
    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
    Route::get('/challenges/{id}', [ChallengeController::class, 'show'])->name('challenges.show');
    Route::post('/challenges/{id}/join', [ChallengeController::class, 'join'])->name('challenges.join');
    Route::post('/challenges/{id}/payment', [ChallengeController::class, 'makePayment'])->name('challenges.payment');

    // Groups
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{group}/approve/{memberId}', [GroupController::class, 'approveMember'])->name('groups.approve');
    Route::post('/groups/{group}/reject/{memberId}', [GroupController::class, 'rejectMember'])->name('groups.reject');

    // Materials
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('materials.show');
    Route::post('/materials/{id}/direct-purchase', [MaterialController::class, 'directPurchase'])->name('materials.direct-purchase');
    Route::post('/materials/{id}/lipa-kidogo', [MaterialController::class, 'lipaKidogoPurchase'])->name('materials.lipa-kidogo');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');

    // Testimonials
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/challenges', [AdminController::class, 'challenges'])->name('admin.challenges');
    Route::get('/challenges/create', [AdminController::class, 'createChallenge'])->name('admin.challenges.create');
    Route::post('/challenges', [AdminController::class, 'storeChallenge'])->name('admin.challenges.store');
    Route::get('/materials', [AdminController::class, 'materials'])->name('admin.materials');
    Route::get('/materials/create', [AdminController::class, 'createMaterial'])->name('admin.materials.create');
    Route::post('/materials', [AdminController::class, 'storeMaterial'])->name('admin.materials.store');
    Route::get('/materials/{id}/edit', [AdminController::class, 'editMaterial'])->name('admin.materials.edit');
    Route::put('/materials/{id}', [AdminController::class, 'updateMaterial'])->name('admin.materials.update');
    Route::delete('/materials/{id}', [AdminController::class, 'deleteMaterial'])->name('admin.materials.delete');
    Route::patch('/materials/{id}/toggle', [AdminController::class, 'toggleMaterialStatus'])->name('admin.materials.toggle');
    Route::get('/penalties', [AdminController::class, 'penalties'])->name('admin.penalties');
    Route::patch('/penalties/{id}/resolve', [AdminController::class, 'resolvePenalty'])->name('admin.penalties.resolve');
    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/groups', [AdminController::class, 'groups'])->name('admin.groups');
    Route::patch('/groups/{id}/approve', [AdminController::class, 'approveGroup'])->name('admin.groups.approve');
    Route::patch('/groups/{id}/reject', [AdminController::class, 'rejectGroup'])->name('admin.groups.reject');
    Route::patch('/groups/{id}/deactivate', [AdminController::class, 'deactivateGroup'])->name('admin.groups.deactivate');
    Route::get('/groups/{id}/edit', [AdminController::class, 'editGroup'])->name('admin.groups.edit');
    Route::put('/groups/{id}', [AdminController::class, 'updateGroup'])->name('admin.groups.update');

    // Admin Notification Management
    Route::prefix('notifications')->name('admin.notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::get('/create', [AdminNotificationController::class, 'create'])->name('create');
        Route::post('/', [AdminNotificationController::class, 'store'])->name('store');
        Route::get('/{notification}', [AdminNotificationController::class, 'show'])->name('show');
        Route::delete('/{notification}', [AdminNotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/bulk-delete', [AdminNotificationController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/send-to-all', [AdminNotificationController::class, 'sendToAll'])->name('send-to-all');
    });

    // Testimonials
    Route::get('/testimonials', [TestimonialController::class, 'adminIndex'])->name('admin.testimonials');
    Route::patch('/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('admin.testimonials.approve');
    Route::patch('/testimonials/{testimonial}/reject', [TestimonialController::class, 'reject'])->name('admin.testimonials.reject');
    Route::patch('/testimonials/{testimonial}/feature', [TestimonialController::class, 'feature'])->name('admin.testimonials.feature');
    Route::patch('/testimonials/{testimonial}/unfeature', [TestimonialController::class, 'unfeature'])->name('admin.testimonials.unfeature');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('admin.testimonials.destroy');
});
