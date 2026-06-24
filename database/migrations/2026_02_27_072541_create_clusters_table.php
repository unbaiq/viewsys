<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clusters', function (Blueprint $table) {

            $table->id();

            // Company
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            // Cluster Details
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('location')->nullable();

            // Layout Type
            $table->enum('type', [
                'fullscreen',
                'half',
                'sidebar',
                'header',
                'ticker',
                'grid',
                'triple',
                'menu'
            ])->default('fullscreen');

            // Optional Text Areas
            $table->string('header_text')->nullable();
            $table->text('ticker_text')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};