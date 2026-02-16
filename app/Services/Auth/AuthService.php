<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\PasswordResetDTO;
use App\DTOs\Auth\SendPasswordResetDTO;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class AuthService
{
    public function __construct(
        private PasswordResetService $resetPasswordService
    ) {
        //
    }

    /**
     * Login a user.
     * 
     * @return bool|array{token: string, user: UserResource}
     */
    public function login(LoginDTO $loginDTO): bool|array
    {
        $user = User::with('role')
            ->where('email', $loginDTO->email)
            ->first();

        if (! $user || ! Hash::check($loginDTO->password, $user->password)) {
            return false;
        }

        $abilities = $user->role?->permissions->pluck('middleware')->toArray() ?? [];
        $token = $user->createToken('authToken', $abilities, now()->addHour());

        return [
            'token' => $token->plainTextToken,
            'user'  => new UserResource($user),
        ];
    }

    /**
     * Send a reset password email to the user.
     */
    public function sendPasswordReset(SendPasswordResetDTO $sendPasswordResetDTO): void
    {
        $this->resetPasswordService->sendEmail($sendPasswordResetDTO);
    }

    /**
     * Reset the user password.
     */
    public function resetPassword(PasswordResetDTO $passwordResetDTO): bool
    {
        return $this->resetPasswordService->reset($passwordResetDTO);
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
