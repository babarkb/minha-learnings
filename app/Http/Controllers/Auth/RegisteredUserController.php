<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->registerUser(
            name: (string) $request->string('name'),
            email: (string) $request->string('email'),
            password: (string) $request->string('password'),
        );

        $this->authService->loginUser($user);

        $request->session()->regenerate();

        return response()->api(
            message: __('Account created successfully.'),
            data: [
                'user' => UserResource::make($user)->resolve(),
            ],
            meta: [
                'redirectTo' => fn () => redirect(route('dashboard', absolute: false)),
            ],
        );
    }
}
