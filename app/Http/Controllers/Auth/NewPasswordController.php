<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(NewPasswordRequest $request): RedirectResponse
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = $this->authService->resetPassword([
            'email' => (string) $request->string('email'),
            'password' => (string) $request->string('password'),
            'password_confirmation' => (string) $request->string('password_confirmation'),
            'token' => (string) $request->string('token'),
        ]);

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            return response()->api(
                message: __($status),
                meta: [
                    'redirectTo' => fn () => redirect()->route('login'),
                    'flash' => ['status' => __($status)],
                ],
            );
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
