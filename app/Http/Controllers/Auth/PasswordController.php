<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;

class PasswordController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * Update the user's password.
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->authService->updatePassword(
            $request->user(),
            (string) $request->string('password'),
        );

        return response()->api(
            message: __('Password updated successfully.'),
            meta: [
                'redirectTo' => fn () => back(),
                'flash' => ['status' => __('Password updated successfully.')],
            ],
        );
    }
}
