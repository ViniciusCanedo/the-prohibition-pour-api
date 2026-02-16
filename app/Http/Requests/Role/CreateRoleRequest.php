<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

use App\Enums\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;

final class CreateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'permissions'   => ['required', 'array'],
            'permissions.*' => ['required', 'string', 'in:'.implode(',', collect(PermissionEnum::cases())->map(function (PermissionEnum $permission): string {
                return $permission->name;
            })->toArray())],
        ];
    }
}
