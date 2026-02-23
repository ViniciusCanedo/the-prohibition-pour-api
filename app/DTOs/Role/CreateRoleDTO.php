<?php

declare(strict_types=1);

namespace App\DTOs\Role;

final class CreateRoleDTO
{
    /**
     * @param  array<string>  $permissions
     */
    public function __construct(
        public string $name,
        public array $permissions,
    ) {}
}
