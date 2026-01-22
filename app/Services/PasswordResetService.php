<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\Auth\PasswordResetMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

final class PasswordResetService
{
    private string $token;

    public function __construct()
    {
        //
    }

    /**
     * Send a reset password email to the user.
     */
    public function sendEmail(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            return;
        }

        $this->generateToken($user->id);

        Mail::to($email)->send(new PasswordResetMail($this->token));
    }

    public function reset(string $token, string $password): bool
    {
        $cachedToken = Cache::get("reset_password_token:{$token}");

        if (! $cachedToken) {
            return false;
        }

        Cache::forget("reset_password_token:{$token}");

        $user = User::find($cachedToken);

        if (! $user) {
            return false;
        }

        $user->password = Hash::make($password);
        $user->save();

        return true;
    }

    private function generateToken(string $userId): void
    {
        $token = bin2hex(random_bytes(16));

        Cache::set("reset_password_token:{$token}", $userId, 60 * 15);
        $this->token = $token;
    }
}
