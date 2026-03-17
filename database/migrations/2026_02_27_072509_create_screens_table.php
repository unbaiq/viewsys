<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('screens', function (Blueprint $table) {

            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            // unique device id from flutter app
            $table->string('device_id')->unique();

            // authentication token for device
            $table->string('device_token')->nullable();

            // physical location
            $table->string('location')->nullable();

            // portrait / landscape
            $table->string('orientation')->default('landscape');

            // online/offline
            $table->boolean('status')->default(false);

            // content version for sync
            $table->integer('content_version')->default(1);

            // device info
            $table->string('ip_address')->nullable();
            $table->string('app_version')->nullable();
            $table->string('device_model')->nullable();

            // storage info from device
            $table->string('storage_free')->nullable();

            // last ping
            $table->timestamp('last_seen')->nullable();
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};