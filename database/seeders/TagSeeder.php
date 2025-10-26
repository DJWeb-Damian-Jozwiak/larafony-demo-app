<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Models\Tag;

class TagSeeder
{
    public function run(): void
    {
        $tags = ['php', 'clean-code', 'larafony', 'framework'];

        array_walk($tags, fn(string $tag) => new Tag()->fill(['name' => $tag])->save());
    }
}
