<?php

declare(strict_types=1);

use App\Enums\PermissionEnum;
use App\Models\Role;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

describe('Role', function () {
    describe('GET /roles', function () {
        it('can list roles', function () {
            Role::factory()->count(3)->create();

            getJson(route('getRoles'))
                ->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'permissions',
                        ],
                    ],
                ]);
        });
    });

    describe('POST /roles', function () {
        it('can create a role with permissions', function () {
            $roleData = [
                'name'        => 'Admin',
                'permissions' => [
                    PermissionEnum::MANAGE_USERS->name,
                    PermissionEnum::MANAGE_ROLES->name,
                ],
            ];

            postJson(route('postRole'), $roleData)
                ->assertCreated()
                ->assertJsonStructure([
                    'message',
                    'role' => [
                        'id',
                        'name',
                        'permissions',
                    ],
                ])
                ->assertJson(['message' => 'Função criada com sucesso']);

            $this->assertDatabaseHas('roles', [
                'name' => 'Admin',
            ]);
        });

        it('cannot create a role with invalid data', function () {
            postJson(route('postRole'), [])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['name', 'permissions']);
        });
    });

    describe('GET /roles/{id}', function () {
        it('can show a role', function () {
            $createResponse = postJson(route('postRole'), [
                'name'        => 'Manager',
                'permissions' => [
                    PermissionEnum::MANAGE_POSTS->name,
                ],
            ]);

            $roleId = $createResponse->json('role.id');

            getJson(route('getRole', $roleId))
                ->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'permissions',
                    ],
                ]);
        });

        it('returns 404 for non-existing role', function () {
            getJson(route('getRole', 'non-existing-id'))
                ->assertNotFound();
        });
    });

    describe('PUT /roles/{id}', function () {
        it('can update a role and its permissions', function () {
            $createResponse = postJson(route('postRole'), [
                'name'        => 'Editor',
                'permissions' => [
                    PermissionEnum::MANAGE_POSTS->name,
                ],
            ]);

            $roleId = $createResponse->json('role.id');

            $updateData = [
                'name'        => 'Content Editor',
                'permissions' => [
                    PermissionEnum::MANAGE_POSTS->name,
                    PermissionEnum::MANAGE_COMMENTS->name,
                ],
            ];

            putJson(route('putRole', $roleId), $updateData)
                ->assertOk()
                ->assertJson([
                    'message' => 'Função atualizada com sucesso',
                ]);

            $this->assertDatabaseHas('roles', [
                'id'   => $roleId,
                'name' => 'Content Editor',
            ]);
        });
    });

    describe('DELETE /roles/{id}', function () {
        it('can delete a role', function () {
            $createResponse = postJson(route('postRole'), [
                'name'        => 'Temporary',
                'permissions' => [
                    PermissionEnum::MANAGE_USERS->name,
                ],
            ]);

            $roleId = $createResponse->json('role.id');

            deleteJson(route('deleteRole', $roleId))
                ->assertOk()
                ->assertJson(['message' => 'Função excluída com sucesso']);

            $this->assertDatabaseMissing('roles', [
                'id' => $roleId,
            ]);
        });

        it('returns 404 when deleting non-existing role', function () {
            deleteJson(route('deleteRole', 'non-existing-id'))
                ->assertNotFound();
        });
    });
});
