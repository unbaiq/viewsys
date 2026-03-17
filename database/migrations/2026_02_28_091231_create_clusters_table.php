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

            // Company (Multi-tenant)
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            // Cluster info
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();

            // Metadata
            $table->string('location')->nullable();

            // Layout type
            $table->string('type')->default('fullscreen');

            // Layout content
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

            // Indexes
            $table->index(['company_id','is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};