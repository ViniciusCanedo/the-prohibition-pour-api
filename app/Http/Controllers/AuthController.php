<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\SendPasswordResetRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
        //
    }

    /**
     * Login a user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginDTO = $request->toDTO();

        $data = $this->authService->login($loginDTO);

        if (! $data) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Send a reset password email to the user.
     */
    public function sendPasswordResetEmail(SendPasswordResetRequest $request): JsonResponse
    {
        $sendPasswordResetDTO = $request->toDTO();

        $this->authService->sendPasswordReset($sendPasswordResetDTO);

        return response()->json([
            'message' => 'Solicitação processada. Em alguns minutos, você receberá um e-mail com as instruções para redefinir sua senha.',
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Reset the user password.
     */
    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        $passwordResetDTO = $request->toDTO();

        $resetSuccess = $this->authService->resetPassword($passwordResetDTO);

        if (! $resetSuccess) {
            return response()->json([
                'message' => 'Token inválido ou expirado.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'message' => 'Senha redefinida com sucesso.',
        ], Response::HTTP_OK);
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $this->authService->logout($user);
        }

        return response()->json([
            'message' => 'Logged out successfully',
        ], Response::HTTP_OK);
    }
}
