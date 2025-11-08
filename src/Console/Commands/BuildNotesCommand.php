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

        $this->output->writeln('Demo user: demo@example.com');

        // Server selection
        $server = $this->output->select(
            ['Apache', 'Nginx', 'PHP Built-in Server'],
            'Which web server would you like to use?'
        );

        $this->output->writeln('');

        match ($server) {
            'Apache' => $this->showInstructions('apache.stub'),
            'Nginx' => $this->showInstructions('nginx.stub'),
            'PHP Built-in Server' => $this->showInstructions('php-server.stub'),
        };

        $this->output->success('Installation complete 🎉');

        return 0;
    }

    private function showInstructions(string $stubFile): void
    {
        $stubPath = dirname(__DIR__, 2) . '/stubs/' . $stubFile;

        if (!file_exists($stubPath)) {
            $this->output->error("Stub file not found: {$stubFile}");
            return;
        }

        $content = file_get_contents($stubPath);
        $docRoot = dirname(__DIR__, 2) . '/public';

        $content = str_replace('{{DOC_ROOT}}', $docRoot, $content);

        $this->output->info($content);
    }
}
