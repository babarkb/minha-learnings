<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration
{
    public function up(): void
    {
        Schema::create('worksheet_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('worksheet_id')->constrained('worksheets')->cascadeOnDelete();
            $table->unsignedInteger('section_index');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worksheet_images');
    }
};
