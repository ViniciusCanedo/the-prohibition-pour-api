<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('middleware')->unique();
            $table->string('label');
            $table->timestamps();
        });

        DB::table('permissions')->insert([
            [
                'id'         => Str::uuid7(),
                'middleware' => 'manage.posts',
                'label'      => 'Gerenciamento de postagens',
            ],
            [
                'id'         => Str::uuid7(),
                'middleware' => 'manage.users',
                'label'      => 'Gerenciamento de usuários',
            ],
            [
                'id'         => Str::uuid7(),
                'middleware' => 'manage.roles',
                'label'      => 'Gerenciamento de funções',
            ],
            [
                'id'         => Str::uuid7(),
                'middleware' => 'manage.tags',
                'label'      => 'Gerenciamento de tags',
            ],
            [
                'id'         => Str::uuid7(),
                'middleware' => 'manage.comments',
                'label'      => 'Gerenciamento de comentários',
            ],
            [
                'id'         => Str::uuid7(),
                'middleware' => 'manage.settings',
                'label'      => 'Gerenciamento de configurações do sistema',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
