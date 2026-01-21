<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class)->using(RecipeTag::class)->withPivot('id');
    }
}
