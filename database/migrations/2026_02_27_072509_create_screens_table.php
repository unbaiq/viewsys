<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screens', function (Blueprint $table) {

            $table->id();

            // Company relation
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            // Basic Info
            $table->string('name');

            // Unique device identity
            $table->string('device_id')->unique();

            // Secure device token
            $table->string('device_token')->unique()->nullable();
            $table->timestamp('token_generated_at')->nullable();

            // Physical / UI config
            $table->string('location')->nullable();
            $table->enum('orientation', ['landscape', 'portrait'])
                ->default('landscape');

            // Device status
            $table->enum('status', ['offline', 'online', 'inactive'])
                ->default('inactive');

            // Sync + Versioning
            $table->unsignedInteger('content_version')->default(1);
            $table->unsignedInteger('schedule_version')->default(1);
            $table->unsignedInteger('media_version')->default(1);
            $table->unsignedInteger('layout_version')->default(1);

            // Command system
            $table->json('commands')->nullable();

            // Action flags
            $table->boolean('request_screenshot')->default(false);
            $table->boolean('restart_requested')->default(false);

            // Device Info
            $table->ipAddress('ip_address')->nullable();
            $table->string('app_version')->nullable();
            $table->string('device_model')->nullable();

            // Storage + Health
            $table->unsignedBigInteger('storage_free')->nullable();
            $table->unsignedTinyInteger('battery_level')->nullable();
            $table->boolean('is_charging')->nullable();

            // Location Tracking
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('location_updated_at')->nullable();

            // Screenshot Tracking
            $table->string('last_screenshot')->nullable();
            $table->timestamp('last_screenshot_at')->nullable();

            // Heartbeat
            $table->timestamp('last_seen')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('company_id');
            $table->index('status');
            $table->index('content_version');
            $table->index('schedule_version');
            $table->index('media_version');
            $table->index('layout_version');

            // Prevent duplicate device per company
            $table->unique(['company_id', 'device_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};