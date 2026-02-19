<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\DTOs\Auth\PasswordResetDTO;
use Illuminate\Foundation\Http\FormRequest;

final class PasswordResetRequest extends FormRequest
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
            'token'    => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    /**
     * Get the data from the request.
     */
    public function toDTO(): PasswordResetDTO
    {
        $data = $this->validated();

        return new PasswordResetDTO(
            $data['token'],
            $data['password'],
        );
    }
}
