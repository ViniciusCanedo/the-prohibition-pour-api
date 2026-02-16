<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\DTOs\Auth\PasswordResetDTO;
use App\DTOs\Auth\SendPasswordResetDTO;
use App\Mail\Auth\PasswordResetMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

final class PasswordResetService
{
    private const TOKEN_TTL = 900;

    private const TOKEN_PREFIX = 'reset_password:token:';

    private const USER_PREFIX = 'reset_password:user:';

    public function sendEmail(SendPasswordResetDTO $sendPasswordResetDTO): void
    {
        $user = User::where('email', $sendPasswordResetDTO->email)->first();

        if (! $user) {
            return;
        }

        $this->invalidateUserToken($user->id);

        $token = $this->generateToken();

        $this->storeToken($user->id, $token);

        Mail::to($sendPasswordResetDTO->email)->queue(new PasswordResetMail($token));
    }

    /**
     * Reset the user password.
     */
    public function reset(PasswordResetDTO $passwordResetDTO): bool
    {
        $userId = $this->getUserIdByToken($passwordResetDTO->token);

        if (! $userId) {
            return false;
        }

        $user = User::find($userId);

        if (! $user) {
            return false;
        }

        $user->update([
            'password' => Hash::make($passwordResetDTO->password),
        ]);

        $this->invalidateUserToken($userId);

        return true;
    }

    private function generateToken(): string
    {
        return Str::random(64);
    }

    private function storeToken(string $userId, string $token): void
    {
        $hashedToken = $this->hashToken($token);

        Cache::put(
            self::TOKEN_PREFIX.$hashedToken,
            $userId,
            self::TOKEN_TTL
        );

        Cache::put(
            self::USER_PREFIX.$userId,
            $hashedToken,
            self::TOKEN_TTL
        );
    }

    private function getUserIdByToken(string $token): ?string
    {
        return Cache::get(
            self::TOKEN_PREFIX.$this->hashToken($token)
        );
    }

    private function invalidateUserToken(string $userId): void
    {
        $hashedToken = Cache::get(
            self::USER_PREFIX.$userId
        );

        if ($hashedToken) {
            Cache::forget(
                self::TOKEN_PREFIX.$hashedToken
            );

            Cache::forget(
                self::USER_PREFIX.$userId
            );
        }
    }

    private function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }
}
