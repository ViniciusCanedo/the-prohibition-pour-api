<?php

declare(strict_types=1);

namespace App\Services\Role;

use App\DTOs\Role\CreateRoleDTO;
use App\DTOs\Role\UpdateRoleDTO;
use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

final class RoleService
{
    /**
     * @return Collection<int, Role>
     */
    public function list(): Collection
    {
        return Role::with('permissions')->get();
    }

    public function show(string $id): Role
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function create(CreateRoleDTO $createRoleDTO): Role
    {
        $role = new Role();
        $role->name = $createRoleDTO->name;
        $role->save();

        $permissionIds = $this->getPermissionIdsFromEnumNames($createRoleDTO->permissions);
        $role->permissions()->sync($permissionIds);

        return $role->load('permissions');
    }

    public function update(UpdateRoleDTO $updateRoleDTO, string $id): Role
    {
        $role = Role::findOrFail($id);

        if ($updateRoleDTO->name !== null) {
            $role->name = $updateRoleDTO->name;
        }

        $role->save();

        if ($updateRoleDTO->permissions !== null) {
            $permissionIds = $this->getPermissionIdsFromEnumNames($updateRoleDTO->permissions);
            $role->permissions()->sync($permissionIds);
        }

        return $role->load('permissions');
    }

    public function delete(string $id): void
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        $role->delete();
    }

    /**
     * @param  array<string>  $names
     * @return array<string>
     */
    private function getPermissionIdsFromEnumNames(array $names): array
    {
        $middlewares = [];

        foreach ($names as $name) {
            foreach (PermissionEnum::cases() as $case) {
                if ($case->name === $name) {
                    $middlewares[] = $case->value;

                    break;
                }
            }
        }

        if ($middlewares === []) {
            return [];
        }

        return Permission::whereIn('middleware', $middlewares)->pluck('id')->all();
    }
}
