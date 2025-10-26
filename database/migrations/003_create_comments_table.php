<?php

declare(strict_types=1);

use Larafony\Framework\Database\Base\Migrations\Migration;
use Larafony\Framework\Database\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $sql = Schema::create('comments', function ($table) {
            $table->id();
            $table->integer('note_id')->nullable(false);
            $table->string('author');
            $table->text('content');
            $table->timestamps();

            $table->index('note_id');
        });

        Schema::execute($sql);
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
