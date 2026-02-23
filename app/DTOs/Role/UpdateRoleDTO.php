<?php

declare(strict_types=1);

namespace App\DTOs\Role;

final class UpdateRoleDTO
{
    /**
     * @param  array<string>  $permissions
     */
    public function __construct(
        public ?string $name = null,
        public ?array $permissions = null,
    ) {}
}
