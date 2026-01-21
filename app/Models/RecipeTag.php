<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class RecipeTag extends Pivot
{
    protected $table = 'recipe_tag';

    protected $guarded = [];
}
