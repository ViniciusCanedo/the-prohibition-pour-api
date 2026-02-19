<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\postJson;

describe('Auth', function () {
    describe('POST /auth/login', function () {
        it('can login with valid credentials', function () {
            $user = User::factory()->create([
                'password' => 'password',
            ]);

            postJson(route('login'), [
                'email'    => $user->email,
                'password' => 'password',
            ])->assertOk()
                ->assertJsonStructure([
                    'token',
                    'user',
                ]);
        });

        it('cannot login with invalid credentials', function () {
            $user = User::factory()->create([
                'password' => 'password',
            ]);

            postJson(route('login'), [
                'email'    => $user->email,
                'password' => 'wrong-password',
            ])->assertUnauthorized()
                ->assertJson([
                    'message' => 'Credenciais inválidas.',
                ]);
        });

        it('cannot login with missing fields', function () {
            postJson(route('login'), [])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['email', 'password']);
        });
    });

    describe('POST /auth/send-reset-password-email', function () {
        it('can send reset password email', function () {
            $user = User::factory()->create();

            postJson(route('sendPasswordResetEmail'), [
                'email' => $user->email,
            ])->assertAccepted()
                ->assertJson(['message' => 'Solicitação processada. Em alguns minutos, você receberá um e-mail com as instruções para redefinir sua senha.']);
        });

        it('cannot send reset password email with missing email', function () {
            postJson(route('sendPasswordResetEmail'), [])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['email']);
        });

        it('returns 202 even with non-existing email (security)', function () {
            postJson(route('sendPasswordResetEmail'), [
                'email' => 'non-existing@email.com',
            ])->assertAccepted()
                ->assertJson(['message' => 'Solicitação processada. Em alguns minutos, você receberá um e-mail com as instruções para redefinir sua senha.']);
        });
    });

    describe('POST /auth/reset-password/{token}', function () {
        it('can reset password with valid token', function () {
            $user = User::factory()->create();
            $token = 'valid-token';

            $hashedToken = hash('sha256', $token);
            Cache::put(
                'reset_password:token:'.$hashedToken,
                $user->id,
                900
            );

            postJson(route('resetPassword', $token), [
                'token'                 => $token,
                'email'                 => $user->email,
                'password'              => 'new-password',
                'password_confirmation' => 'new-password',
            ])->assertOk()
                ->assertJson(['message' => 'Senha redefinida com sucesso.']);
        });

        it('cannot reset password with invalid token', function () {
            $user = User::factory()->create();

            postJson(route('resetPassword'), [
                'token'                 => 'invalid-token',
                'password'              => 'new-password',
                'password_confirmation' => 'new-password',
            ])->assertUnauthorized()
                ->assertJson(['message' => 'Token inválido ou expirado.']);
        });

        it('cannot reset password with missing fields', function () {
            $user = User::factory()->create();
            $token = 'valid-token';
            Cache::put("reset_password_token:{$token}", $user->id, 60 * 15);

            postJson(route('resetPassword', $token), [])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['token', 'password']);
        });
    });

    describe('POST /auth/logout', function () {
        it('can logout', function () {
            $user = User::factory()->create();
            $token = $user->createToken('auth_token')->plainTextToken;

            postJson(route('logout'), [], [
                'Authorization' => 'Bearer '.$token,
            ])
                ->assertOk()
                ->assertJson(['message' => 'Logged out successfully']);
        });

        it('cannot logout if not authenticated', function () {
            postJson(route('logout'))
                ->assertUnauthorized();
        });
    });
});
