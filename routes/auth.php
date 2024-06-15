<?php

use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(static function (): void {
    Route::get('register', static fn (): \Inertia\Response => (new \App\Http\Controllers\Auth\RegisteredUserController())->create())
        ->name('register');
    Route::post('register', static fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\Auth\RegisteredUserController())->store($request));
    Route::get('login', static fn (): \Inertia\Response => (new \App\Http\Controllers\Auth\AuthenticatedSessionController())->create())
        ->name('login');
    Route::post('login', static fn (\App\Http\Requests\Auth\LoginRequest $loginRequest): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\Auth\AuthenticatedSessionController())->store($loginRequest));
    Route::get('forgot-password', static fn (): \Inertia\Response => (new \App\Http\Controllers\Auth\PasswordResetLinkController())->create())
        ->name('password.request');
    Route::post('forgot-password', static fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\Auth\PasswordResetLinkController())->store($request))
        ->name('password.email');
    Route::get('reset-password/{token}', static fn (\Illuminate\Http\Request $request): \Inertia\Response => (new \App\Http\Controllers\Auth\NewPasswordController())->create($request))
        ->name('password.reset');
    Route::post('reset-password', static fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\Auth\NewPasswordController())->store($request))
        ->name('password.store');
});

Route::middleware('auth')->group(static function (): void {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', static fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\Auth\EmailVerificationNotificationController())->store($request))
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', static fn (): \Inertia\Response => (new \App\Http\Controllers\Auth\ConfirmablePasswordController())->show())
        ->name('password.confirm');
    Route::post('confirm-password', static fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\Auth\ConfirmablePasswordController())->store($request));
    Route::put('password', static fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\Auth\PasswordController())->update($request))->name('password.update');
    Route::post('logout', static fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\Auth\AuthenticatedSessionController())->destroy($request))
        ->name('logout');
});
