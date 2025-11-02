<?php

declare(strict_types=1);

use Larafony\Framework\Database\Base\Migrations\Migration;
use Larafony\Framework\Database\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_tag', function ($table) {
            $table->integer('note_id')->nullable(false);
            $table->integer('tag_id')->nullable(false);

            $table->index('note_id');
            $table->index('tag_id');
        }) |> Schema::execute(...);

        Schema::execute($sql);
    }

    public function down(): void
    {
        Schema::dropIfExists('note_tag');
    }
};
