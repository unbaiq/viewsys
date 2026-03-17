<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('device_logs', function (Blueprint $table) {

            $table->id();
            $table->foreignId('screen_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('status')->nullable();
            $table->timestamp('last_ping')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('app_version')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_logs');
    }
};
