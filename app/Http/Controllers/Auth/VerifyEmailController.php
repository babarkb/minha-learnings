<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $redirect = fn () => redirect()->intended(route('dashboard', absolute: false).'?verified=1');

        if (! $this->authService->markEmailVerified($request->user())) {
            return response()->api(
                message: __('Email already verified.'),
                meta: ['redirectTo' => $redirect],
            );
        }

        return response()->api(
            message: __('Email verified successfully.'),
            meta: ['redirectTo' => $redirect],
        );
    }
}
