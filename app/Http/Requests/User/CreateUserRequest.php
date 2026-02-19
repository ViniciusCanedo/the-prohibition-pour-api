<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\DTOs\User\CreateUserDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CreateUserRequest extends FormRequest
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
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'roleId'   => ['required', 'string', 'exists:roles,id'],
        ];
    }

    public function toDTO(): CreateUserDTO
    {
        $data = $this->validated();

        return new CreateUserDTO(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
            role_id: $data['roleId'] ?? null,
        );
    }
}
