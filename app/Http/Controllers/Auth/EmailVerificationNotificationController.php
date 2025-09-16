<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->api(
                message: __('Email already verified.'),
                meta: [
                    'redirectTo' => fn () => redirect()->intended(route('dashboard', absolute: false)),
                ],
            );
        }

        $this->authService->sendEmailVerification($request->user());

        return response()->api(
            message: __('Verification link sent.'),
            meta: [
                'redirectTo' => fn () => back(),
                'flash' => ['status' => 'verification-link-sent'],
            ],
        );
    }
}
