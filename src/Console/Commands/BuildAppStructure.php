<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Larafony\Framework\Console\Attributes\AsCommand;
use Larafony\Framework\Console\Command;
use Larafony\Framework\Database\Schema;

#[AsCommand('build:app-structure')]
class BuildAppStructure extends Command
{
    public function run(): int
    {
        $this->output->info('Building application structure...');
        Schema::create('notes', function ($table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->integer('user_id')->nullable(false);
            $table->timestamps();

            $table->index('user_id');
        }) |> Schema::execute(...);

        Schema::create('comments', function ($table) {
            $table->id();
            $table->integer('note_id')->nullable(false);
            $table->string('author');
            $table->text('content');
            $table->timestamps();

            $table->index('note_id');
        }) |> Schema::execute(...);

        Schema::create('tags', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

            $table->unique('name');
        }) |> Schema::execute(...);

        Schema::create('note_tag', function ($table) {
            $table->integer('note_id')->nullable(false);
            $table->integer('tag_id')->nullable(false);

            $table->index('note_id');
            $table->index('tag_id');
        }) |> Schema::execute(...);

        return 0;
    }
}