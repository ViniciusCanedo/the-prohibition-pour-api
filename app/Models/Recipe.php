<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ingredients' => 'array',
            'steps' => 'array',
            'pairings' => 'array',
            'is_alcoholic' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->using(RecipeTag::class)->withPivot('id');
    }
}
