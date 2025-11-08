<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Database\Seeders\UserSeeder;
use App\Database\Seeders\TagSeeder;
use App\Database\Seeders\NoteSeeder;
use Larafony\Framework\Console\Attributes\AsCommand;
use Larafony\Framework\Console\Command;
use PDO;
use PDOException;

#[AsCommand(name: 'build:notes')]
class BuildNotesCommand extends Command
{
    protected string $description = 'Build and setup the Notes application';

    public function run(): int
    {
        $this->output->writeln('');
        $this->output->info('🧱 Larafony Notes Pro+ Installer');
        $this->output->writeln('-----------------------------------');
        $this->output->writeln('');

        $this->call('database:init');
        $this->call('build:app-structure');

        $this->call('app:seed');


        $this->output->writeln('');
        $this->output->success('Installation complete 🎉');
        $this->output->writeln('Demo user: demo@example.com');
        $this->output->writeln('URL: http://larafony.local/notes');
        $this->output->writeln('');

        return 0;
    }
}
