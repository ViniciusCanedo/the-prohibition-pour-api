<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Rating;
use App\Models\Recipe;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = Permission::factory(10)->create();
        $roles = Role::factory(3)->create();

        // Attach permissions to roles
        $roles->each(function ($role) use ($permissions) {
            $role->permissions()->attach($permissions->random(3));
        });

        // Create users with roles
        User::factory(10)->recycle($roles)->create();

        $tags = Tag::factory(10)->create();

        // Create recipes with tags and ratings
        Recipe::factory(20)
            ->has(Rating::factory()->count(5))
            ->create()
            ->each(function ($recipe) use ($tags) {
                $recipe->tags()->attach($tags->random(3));
            });
    }
}
