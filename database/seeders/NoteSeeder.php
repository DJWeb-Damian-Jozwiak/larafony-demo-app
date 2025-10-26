<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Models\Note;
use App\Models\User;
use App\Models\Tag;

class NoteSeeder
{
    public function run(): void
    {
        $notes = [
            [
                'title' => 'Welcome to Larafony Framework',
                'content' => 'Larafony is a modern PHP 8.5 framework built from scratch with PSR standards at its core.',
                'user_id' => 1,
                'tag_ids' => [3, 4], // larafony, framework
            ],
            [
                'title' => 'Clean Code Principles',
                'content' => 'Writing clean, maintainable code is essential for long-term project success.',
                'user_id' => 1,
                'tag_ids' => [1, 2], // php, clean-code
            ],
            [
                'title' => 'PSR Standards Matter',
                'content' => 'Following PSR standards ensures your code is interoperable and follows best practices.',
                'user_id' => 1,
                'tag_ids' => [1, 4], // php, framework
            ],
        ];

        array_walk($notes, function ($note) {
            new Note()->fill($note)->save();
        });
    }
}
