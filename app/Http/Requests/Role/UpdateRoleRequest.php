<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

use App\DTOs\Role\UpdateRoleDTO;
use App\Enums\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'          => ['sometimes', 'string', 'max:255'],
            'permissions'   => ['sometimes', 'array'],
            'permissions.*' => ['required_with:permissions', 'string', 'in:'.implode(',', collect(PermissionEnum::cases())->map(function (PermissionEnum $permission): string {
                return $permission->name;
            })->toArray())],
        ];
    }

    public function toDTO(): UpdateRoleDTO
    {
        $data = $this->validated();

        return new UpdateRoleDTO(
            $data['name'] ?? null,
            $data['permissions'] ?? null,
        );
    }
}
