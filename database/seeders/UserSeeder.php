<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Models\User;

class UserSeeder
{
    public function run(): void
    {
        new User()->fill(['name' => 'Demo User', 'email' => 'demo@example.com'])->save();
    }
}
