<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

final class SendPasswordResetDTO
{
    public function __construct(
        public readonly string $email,
    ) {
        //
    }
}
