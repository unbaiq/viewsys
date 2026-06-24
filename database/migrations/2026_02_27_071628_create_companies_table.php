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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Owner (optional if not in UI)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Basic
            $table->string('name');

            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();

            // Business
            $table->string('industry')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();

            // Address
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();

            // Plan
            $table->string('plan')->default('starter');
            $table->integer('screen_limit')->default(5);
            $table->integer('storage_limit')->default(10240);
            $table->integer('user_limit')->default(5);

            // Dates
            $table->date('plan_start_date')->nullable();
            $table->date('plan_end_date')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_trial')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
