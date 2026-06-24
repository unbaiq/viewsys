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
        Schema::create('schedules', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELATIONS
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('screen_id')
                ->nullable() // ✅ allow cluster-based schedule
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('cluster_id')
                ->nullable() // ✅ IMPORTANT (used in your controller)
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('playlist_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | DATE RANGE (OPTIONAL = ALWAYS PLAY)
            |--------------------------------------------------------------------------
            */

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TIME RANGE (OPTIONAL)
            |--------------------------------------------------------------------------
            */

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DAYS (JSON ARRAY → ["mon","tue"])
            |--------------------------------------------------------------------------
            */

            $table->json('days_of_week')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FLAGS
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_default')->default(false); // ✅ fallback schedule

            /*
            |--------------------------------------------------------------------------
            | PRIORITY
            |--------------------------------------------------------------------------
            */

            $table->integer('priority')->default(1);

            /*
            |--------------------------------------------------------------------------
            | PERFORMANCE INDEXES 🔥
            |--------------------------------------------------------------------------
            */

            $table->index(['screen_id', 'cluster_id']);
            $table->index(['start_date', 'end_date']);
            $table->index('priority');

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};