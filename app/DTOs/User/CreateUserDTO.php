<?php

declare(strict_types=1);

namespace App\DTOs\User;

final class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role_id,
    ) {
        //
    }
}
