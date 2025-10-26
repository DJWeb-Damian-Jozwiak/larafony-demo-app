<?php

declare(strict_types=1);

use Larafony\Framework\Database\Base\Migrations\Migration;
use Larafony\Framework\Database\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $sql = Schema::create('notes', function ($table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->integer('user_id')->nullable(false);
            $table->timestamps();

            $table->index('user_id');
        });

        Schema::execute($sql);
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
