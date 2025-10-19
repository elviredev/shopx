<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\KycRequestController;
use Illuminate\Support\Facades\Route;

// ex: pour URL - admin/login, pour name - admin.login
Route::middleware('guest:admin')
-> prefix('admin')
->as('admin.')
->group(function () {
  Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

  Route::post('login', [AuthenticatedSessionController::class, 'store']);

  Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

  Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

  Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

  Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');
});

Route::middleware('auth:admin')
  ->prefix('admin')
  ->as('admin.')
  ->group(function () {
  Route::get('verify-email', EmailVerificationPromptController::class)
    ->name('verification.notice');

  Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

  Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

  Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->name('password.confirm');

  Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

//  Route::put('password', [PasswordController::class, 'update'])->name('password.update');

  Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

  /** Profile Routes */
  Route::get('/profile', [ProfileController::class, 'index'])
    ->name('profile.index');
  Route::put('/profile', [ProfileController::class, 'profileUpdate'])
    ->name('profile.update');
  Route::put('/profile/password', [ProfileController::class, 'passwordUpdate'])
    ->name('password.update');

  /** KYC Routes */
  route::get('/kyc-requests', [KycRequestController::class, 'index'])->name('kyc.index');
  route::get('/kyc-requests/pending', [KycRequestController::class, 'pending'])->name('kyc.pending');
  route::get('/kyc-requests/rejected', [KycRequestController::class, 'rejected'])->name('kyc.rejected');
  route::get('/kyc-requests/approved', [KycRequestController::class, 'approved'])->name('kyc.approved');
  route::get('/kyc-requests/{kyc_request}', [KycRequestController::class, 'show'])->name('kyc.show');
  route::get('/kyc-requests/download/{kyc_request}', [KycRequestController::class, 'download'])->name('kyc.download');
  route::put('/kyc-requests/{kyc_request}/update', [KycRequestController::class, 'update'])->name('kyc.update');
});

/*================= Dashboard ADMIN =================*/
Route::get('/admin/dashboard', function () {
  return view('admin.dashboard.index');
})->middleware(['auth:admin', 'verified'])->name('admin.dashboard');
