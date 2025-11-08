<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Models\User;
use Larafony\Framework\Database\ORM\Entities\Permission;
use Larafony\Framework\Database\ORM\Entities\Role;

class UserSeeder
{
    public function run(): void
    {
        // Create admin user
        $admin = new User();
        $admin->email = 'admin@example.com';
        $admin->username = 'admin';
        $admin->password = 'password'; // Auto-hashed with Argon2id
        $admin->is_active = 1;
        $admin->save();

        // Create regular user
        $user = new User();
        $user->email = 'user@example.com';
        $user->username = 'user';
        $user->password = 'password'; // Auto-hashed with Argon2id
        $user->is_active = 1;
        $user->save();

        // Create roles
        $adminRole = new Role();
        $adminRole->name = 'admin';
        $adminRole->description = 'Administrator role';
        $adminRole->save();

        $userRole = new Role();
        $userRole->name = 'user';
        $userRole->description = 'Regular user role';
        $userRole->save();

        // Create permissions
        $addNotePermission = new Permission();
        $addNotePermission->name = 'notes.create';
        $addNotePermission->description = 'Can create notes';
        $addNotePermission->save();

        $editNotePermission = new Permission();
        $editNotePermission->name = 'notes.edit';
        $editNotePermission->description = 'Can edit notes';
        $editNotePermission->save();

        $deleteNotePermission = new Permission();
        $deleteNotePermission->name = 'notes.delete';
        $deleteNotePermission->description = 'Can delete notes';
        $deleteNotePermission->save();

        // Assign roles to users
        $admin->addRole($adminRole);
        $user->addRole($userRole);
    }
}
