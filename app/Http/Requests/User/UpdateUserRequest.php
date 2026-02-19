<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\DTOs\User\UpdateUserDTO;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateUserRequest extends FormRequest
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
            'name'   => 'sometimes|string|max:255',
            'email'  => 'sometimes|email|max:255|unique:users,email,'.$this->route('id'),
            'roleId' => 'sometimes|string|exists:roles,id',
        ];
    }

    public function toDTO(): UpdateUserDTO
    {
        $data = $this->validated();

        return new UpdateUserDTO(
            $data['name'],
            $data['email'],
            $data['roleId'],
        );
    }
}
