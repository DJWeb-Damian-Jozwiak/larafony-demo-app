<?php

declare(strict_types=1);

use Larafony\Framework\Database\Base\Migrations\Migration;
use Larafony\Framework\Database\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $sql = Schema::create('tags', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

            $table->unique('name');
        });

        Schema::execute($sql);
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
