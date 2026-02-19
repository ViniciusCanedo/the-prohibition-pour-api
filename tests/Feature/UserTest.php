<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\User;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

describe('User', function () {
    describe('GET /users', function () {
        it('can list users', function () {
            User::factory()->count(3)->create();

            getJson(route('getUsers'))
                ->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                        ],
                    ],
                ]);
        });
    });

    describe('POST /users', function () {
        it('can create a user', function () {
            $role = Role::factory()->create();
            $userData = [
                'name'                  => 'John Doe',
                'email'                 => 'john@example.com',
                'password'              => 'password',
                'password_confirmation' => 'password',
                'roleId'                => $role->id,
            ];

            postJson(route('postUser'), $userData)
                ->assertCreated()
                ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                    ],
                ])
                ->assertJson(['message' => 'Usuário criado com sucesso']);

            $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        });

        it('cannot create a user with invalid data', function () {
            postJson(route('postUser'), [])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['name', 'email', 'password', 'roleId']);
        });
    });

    describe('GET /users/{id}', function () {
        it('can show a user', function () {
            $user = User::factory()->create();

            getJson(route('getUser', $user->id))
                ->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'role',
                    ],
                ]);
        });

        it('returns 404 for non-existing user', function () {
            getJson(route('getUser', 'non-existing-id'))
                ->assertNotFound();
        });
    });

    describe('PUT /users/{id}', function () {
        it('can update a user', function () {
            $user = User::factory()->create();
            $newRole = Role::factory()->create();

            $updateData = [
                'name'   => 'Jane Doe',
                'email'  => 'jane@example.com',
                'roleId' => $newRole->id,
            ];

            putJson(route('putUser', $user->id), $updateData)
                ->assertOk()
                ->assertJson(['message' => 'Usuário atualizado com sucesso']);

            $this->assertDatabaseHas('users', [
                'id'    => $user->id,
                'name'  => 'Jane Doe',
                'email' => 'jane@example.com',
            ]);
        });
    });

    describe('DELETE /users/{id}', function () {
        it('can delete a user', function () {
            $user = User::factory()->create();

            deleteJson(route('deleteUser', $user->id))
                ->assertOk()
                ->assertJson(['message' => 'Usuário excluído com sucesso']);

            $this->assertDatabaseMissing('users', ['id' => $user->id]);
        });

        it('returns 404 when deleting non-existing user', function () {
            deleteJson(route('deleteUser', 'non-existing-id'))
                ->assertNotFound();
        });
    });
});
