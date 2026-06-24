<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {

            $table->id();

            // COMPANY
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            // USER FRIENDLY NAME
            $table->string('name')->index();

            // UNIQUE STORED FILE NAME
            $table->string('file_name')->unique();

            // TYPE (image / video)
            $table->enum('type', ['image', 'video'])->index();

            // STORAGE PATH
            $table->string('file_path');

            // FILE SIZE (bytes)
            $table->unsignedBigInteger('size');

            // VIDEO ACTUAL DURATION (seconds)
            $table->unsignedInteger('duration')
                ->nullable()
                ->comment('Only for videos');

            // IMAGE DISPLAY DURATION (seconds)
            $table->unsignedInteger('display_duration')
                ->nullable()
                ->comment('Only for images (how long to show)');

            // CREATOR
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            // OPTIONAL: extra composite index for faster filtering
            $table->index(['company_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};