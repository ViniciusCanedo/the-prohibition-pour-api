<?php

declare(strict_types=1);

namespace App\Services\Permission;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

final class PermissionService
{
    /**
     * @return Collection<int, Permission>
     */
    public function list(): Collection
    {
        return Permission::all();
    }
}
