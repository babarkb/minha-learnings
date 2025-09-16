<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    public function attemptLogin(string $email, string $password, bool $remember = false): bool
    {
        return Auth::attempt([
            'email' => $email,
            'password' => $password,
        ], $remember);
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }

    public function registerUser(string $name, string $email, string $password): User
    {
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        event(new Registered($user));

        return $user;
    }

    public function loginUser(User $user, bool $remember = false): void
    {
        Auth::login($user, $remember);
    }

    public function sendResetLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    /**
     * @param  array{email: string, password: string, password_confirmation: string, token: string}  $credentials
     */
    public function resetPassword(array $credentials): string
    {
        return Password::reset(
            $credentials,
            function (User $user, string $password): void {
                $this->updateUserPassword($user, $password);
            }
        );
    }

    public function updatePassword(User $user, string $password): void
    {
        $this->updateUserPassword($user, $password);
    }

    public function confirmPassword(User $user, string $password): bool
    {
        return Auth::guard('web')->validate([
            'email' => $user->email,
            'password' => $password,
        ]);
    }

    public function sendEmailVerification(User $user): void
    {
        $user->sendEmailVerificationNotification();
    }

    public function markEmailVerified(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            return true;
        }

        return false;
    }

    protected function updateUserPassword(User $user, string $password): void
    {
        $user->forceFill([
            'password' => $password,
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
    }
}
