<?php

declare(strict_types=1);

use Larafony\Framework\Database\Base\Migrations\Migration;
use Larafony\Framework\Database\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $sql = Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->unique('email');
            $table->timestamps();
        });

        Schema::execute($sql);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
