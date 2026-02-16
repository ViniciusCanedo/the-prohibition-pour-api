<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\PermissionEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->whenLoaded('role', function (): string {
                return $this->role->name;
            }),
            'permissions' => $this->whenLoaded('role', $this->getRolePermissions()),
        ];
    }

    /**
     * @return list<string>
     */
    private function getRolePermissions(): array
    {
        return $this->role->permissions
            ->pluck('middleware')
            ->map(function (string $middleware): string {
                return PermissionEnum::from($middleware)->name;
            })
            ->values()
            ->all();
    }
}
