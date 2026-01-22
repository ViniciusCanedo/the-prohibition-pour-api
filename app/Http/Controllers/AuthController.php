<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\SendPasswordResetRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\PasswordResetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

final class AuthController extends Controller
{
    public function __construct(private PasswordResetService $resetPasswordService)
    {
        //
    }

    /**
     * Login a user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        ['email' => $email, 'password' => $password] = $request->validated();

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return response()->json([
                'email' => ['Credenciais inválidas.'],
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => new UserResource($user),
        ]);
    }

    /**
     * Send a reset password email to the user.
     */
    public function sendPasswordResetEmail(SendPasswordResetRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->resetPasswordService->sendEmail($validated['email']);

        return response()->json([
            'message' => 'Solicitação processada. Em alguns minutos, você receberá um e-mail com as instruções para redefinir sua senha.',
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Reset the user password.
     */
    public function resetPassword(PasswordResetRequest $request, string $token): JsonResponse
    {
        $validated = $request->validated();

        $resetSuccess = $this->resetPasswordService->reset($token, $validated['password']);

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

        $user?->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], Response::HTTP_OK);
    }
}
