<?php

declare(strict_types=1);

use App\Enums\PermissionEnum;

use function Pest\Laravel\getJson;

describe('Permission', function () {
    describe('GET /permissions', function () {
        it('can list permissions', function () {
            getJson(route('getPermissions'))
                ->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'name',
                            'label',
                        ],
                    ],
                ])
                ->assertJsonFragment([
                    'name' => PermissionEnum::MANAGE_POSTS->name,
                ]);
        });
    });
});
