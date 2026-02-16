<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\PermissionEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'  => PermissionEnum::from($this->middleware)->name,
            'label' => $this->label,
        ];
    }
}
