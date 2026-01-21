<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('description');
            $table->string('image_url');
            $table->jsonb('ingredients');
            $table->jsonb('steps');
            $table->integer('prep_time');
            $table->string('origin_country');
            $table->text('story');
            $table->text('note');
            $table->jsonb('pairings');
            $table->boolean('is_alcoholic');
            $table->string('status');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
