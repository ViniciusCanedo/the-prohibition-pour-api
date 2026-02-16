<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

final class PasswordResetDTO
{
    public function __construct(
        public string $token,
        public string $password,
    ) {}
}
