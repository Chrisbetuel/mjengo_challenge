<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/lang/switch', function (Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['en', 'sw'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Admin login routes
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Password Reset Routes
use App\Http\Controllers\Auth\ResetPasswordController;
Route::get('/forgot-password', [ResetPasswordController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Challenges
    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
    Route::get('/challenges/{id}', [ChallengeController::class, 'show'])->name('challenges.show');
    Route::post('/challenges/{id}/join', [ChallengeController::class, 'join'])->name('challenges.join');
    Route::post('/challenges/{id}/payment', [ChallengeController::class, 'makePayment'])->name('challenges.payment');

    // Materials
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('materials.show');
    Route::post('/materials/{id}/direct-purchase', [MaterialController::class, 'directPurchase'])->name('materials.direct-purchase');
    Route::post('/materials/{id}/lipa-kidogo', [MaterialController::class, 'lipaKidogoPurchase'])->name('materials.lipa-kidogo');

    // Groups
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{group}/approve/{memberId}', [GroupController::class, 'approveMember'])->name('groups.approve');
    Route::post('/groups/{group}/reject/{memberId}', [GroupController::class, 'rejectMember'])->name('groups.reject');

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/challenges', [AdminController::class, 'challenges'])->name('challenges');
        Route::get('/challenges/create', [AdminController::class, 'createChallenge'])->name('challenges.create');
        Route::post('/challenges', [AdminController::class, 'storeChallenge'])->name('challenges.store');
        Route::get('/materials', [AdminController::class, 'materials'])->name('materials');
        Route::get('/materials/create', [AdminController::class, 'createMaterial'])->name('materials.create');
        Route::post('/materials', [AdminController::class, 'storeMaterial'])->name('materials.store');
        Route::get('/materials/{id}/edit', [AdminController::class, 'editMaterial'])->name('materials.edit');
        Route::put('/materials/{id}', [AdminController::class, 'updateMaterial'])->name('materials.update');
        Route::delete('/materials/{id}', [AdminController::class, 'deleteMaterial'])->name('materials.destroy');
        Route::patch('/materials/{id}/toggle-status', [AdminController::class, 'toggleMaterialStatus'])->name('materials.toggle-status');
        Route::get('/penalties', [AdminController::class, 'penalties'])->name('penalties');
        Route::post('/penalties/{id}/resolve', [AdminController::class, 'resolvePenalty'])->name('penalties.resolve');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/groups', [AdminController::class, 'groups'])->name('groups');
        Route::patch('/groups/{id}/approve', [AdminController::class, 'approveGroup'])->name('groups.approve');
        Route::patch('/groups/{id}/reject', [AdminController::class, 'rejectGroup'])->name('groups.reject');
        Route::get('/groups/{id}/edit', [AdminController::class, 'editGroup'])->name('groups.edit');
        Route::put('/groups/{id}', [AdminController::class, 'updateGroup'])->name('groups.update');
    });
});
