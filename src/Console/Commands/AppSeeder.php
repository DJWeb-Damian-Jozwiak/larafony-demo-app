<?php

namespace App\Console\Commands;

use App\Database\Seeders\NoteSeeder;
use App\Database\Seeders\TagSeeder;
use App\Database\Seeders\UserSeeder;
use Larafony\Framework\Console\Attributes\AsCommand;
use Larafony\Framework\Console\Command;

#[AsCommand('app:seed')]
class AppSeeder extends Command
{
    public function run(): int
    {
        $this->output->info('Seeding demo data...');

        $this->output->writeln('✔ UserSeeder');
        new UserSeeder()->run();

        $this->output->writeln('✔ TagSeeder');
        new TagSeeder()->run();

        $this->output->writeln('✔ NoteSeeder');
        new NoteSeeder()->run();

        return 0;
    }
}