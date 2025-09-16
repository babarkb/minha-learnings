<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate($this->authService);

        $request->session()->regenerate();

        return response()->api(
            message: __('Logged in successfully.'),
            data: [
                'user' => UserResource::make($request->user())->resolve(),
            ],
            meta: [
                'redirectTo' => fn () => redirect()->intended(route('dashboard', absolute: false)),
            ],
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authService->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->api(
            message: __('Logged out successfully.'),
            meta: [
                'redirectTo' => fn () => redirect('/'),
            ],
        );
    }
}
