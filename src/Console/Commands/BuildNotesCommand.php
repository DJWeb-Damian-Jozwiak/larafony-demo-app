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

        // Check database connection
        if (!$this->checkDatabaseConnection()) {
            $this->output->warning('⚠️  Cannot connect to database.');
            $this->output->writeln('');

            if (!$this->configureDatabaseInteractively()) {
                $this->output->error('Database configuration failed. Aborting installation.');
                return 1;
            }
        }

        $this->output->success('✅ Database connection successful.');
        $this->output->writeln('');

        // Run migrations
        $this->output->info('Running migrations...');
        try {
            $this->runMigrations();
            $this->output->success('✅ All migrations executed.');
        } catch (\Exception $e) {
            $this->output->error('Migration failed: ' . $e->getMessage());
            return 1;
        }
        $this->output->writeln('');

        // Run seeders
        $this->output->info('Seeding demo data...');

        $this->output->writeln('✔ UserSeeder');
        (new UserSeeder())->run();

        $this->output->writeln('✔ TagSeeder');
        (new TagSeeder())->run();

        $this->output->writeln('✔ NoteSeeder');
        (new NoteSeeder())->run();

        $this->output->writeln('');
        $this->output->success('Installation complete 🎉');
        $this->output->writeln('Demo user: demo@example.com');
        $this->output->writeln('URL: http://larafony.local/notes');
        $this->output->writeln('');

        return 0;
    }

    private function checkDatabaseConnection(): bool
    {
        try {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $database = $_ENV['DB_DATABASE'] ?? 'larafony_notes';
            $username = $_ENV['DB_USERNAME'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';

            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    private function configureDatabaseInteractively(): bool
    {
        $this->output->info('Let\'s configure your database connection:');
        $this->output->writeln('');

        $host = $this->output->question('Database host [127.0.0.1]: ') ?: '127.0.0.1';
        $port = $this->output->question('Database port [3306]: ') ?: '3306';
        $database = $this->output->question('Database name [larafony_notes]: ') ?: 'larafony_notes';
        $username = $this->output->question('Database username [root]: ') ?: 'root';
        $password = $this->output->question('Database password []: ') ?: '';

        // Test connection
        try {
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Create database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // Update .env file
            $this->updateEnvFile([
                'DB_HOST' => $host,
                'DB_PORT' => $port,
                'DB_DATABASE' => $database,
                'DB_USERNAME' => $username,
                'DB_PASSWORD' => $password,
            ]);

            // Update environment variables for current process
            $_ENV['DB_HOST'] = $host;
            $_ENV['DB_PORT'] = $port;
            $_ENV['DB_DATABASE'] = $database;
            $_ENV['DB_USERNAME'] = $username;
            $_ENV['DB_PASSWORD'] = $password;

            $this->output->writeln('');
            $this->output->success('✅ Database configured and created successfully.');
            $this->output->writeln('');

            return true;
        } catch (PDOException $e) {
            $this->output->error('Failed to connect: ' . $e->getMessage());
            return false;
        }
    }

    private function updateEnvFile(array $values): void
    {
        $envPath = __DIR__ . '/../../../.env';

        if (!file_exists($envPath)) {
            $examplePath = __DIR__ . '/../../../.env.example';
            if (file_exists($examplePath)) {
                copy($examplePath, $envPath);
            }
        }

        $envContent = file_exists($envPath) ? file_get_contents($envPath) : '';

        foreach ($values as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envPath, $envContent);
    }

    private function runMigrations(): void
    {
        // Drop all tables first (fresh migration)
        $this->dropAllTables();

        // Run migrations
        $migrationsPath = __DIR__ . '/../../../database/migrations';
        $files = glob($migrationsPath . '/*.php');
        sort($files);

        foreach ($files as $file) {
            $migration = require $file;
            $migration->up();
        }
    }

    private function dropAllTables(): void
    {
        try {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $database = $_ENV['DB_DATABASE'] ?? 'larafony_notes';
            $username = $_ENV['DB_USERNAME'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';

            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Get all tables
            $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

            // Disable foreign key checks
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

            // Drop each table
            foreach ($tables as $table) {
                $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
            }

            // Re-enable foreign key checks
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        } catch (PDOException $e) {
            // If database doesn't exist or tables don't exist, that's fine
        }
    }
}
