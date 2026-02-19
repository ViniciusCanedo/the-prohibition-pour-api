<?php

declare(strict_types=1);

namespace App\DTOs\User;

final class UpdateUserDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $role_id = null,
    ) {
        //
    }
}
