<?php

namespace App\Console\Commands;


use Larafony\Framework\Console\Attributes\AsCommand;
use Larafony\Framework\Console\Command;

#[AsCommand(name: 'test:error')]
class TestErrorCommand extends Command
{
    public function run(array $options = []): int
    {
        $this->output->info('Testing console error handler...');

        // Throw an exception to test the error handler
        throw new \RuntimeException('This is a test exception from console command!');
    }
}
