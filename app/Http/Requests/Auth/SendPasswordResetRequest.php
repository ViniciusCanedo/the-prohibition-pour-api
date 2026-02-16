<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\DTOs\Auth\SendPasswordResetDTO;
use Illuminate\Foundation\Http\FormRequest;

final class SendPasswordResetRequest extends FormRequest
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
            'email' => ['required', 'email'],
        ];
    }

    /**
     * Get the data from the request.
     */
    public function toDTO(): SendPasswordResetDTO
    {
        $data = $this->validated();

        return new SendPasswordResetDTO(
            $data['email'],
        );
    }
}
