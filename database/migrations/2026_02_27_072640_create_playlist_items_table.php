<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('playlist_items', function (Blueprint $table) {
            $table->id();
        
            // 🧠 COMPANY = TENANT
            $table->foreignId('company_id')
                  ->constrained()
                  ->cascadeOnDelete();
        
            // 🎯 TARGETING
            $table->foreignId('screen_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();
        
            $table->foreignId('cluster_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
        
            // 📺 RELATIONS
            $table->foreignId('playlist_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained()->cascadeOnDelete();
        
            // 🎬 SETTINGS
            $table->integer('order')->default(0);
            $table->integer('duration')->nullable();
        
            // 📅 SCHEDULING
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->json('days_of_week')->nullable();
        
            // ⚡ INDEXES
            $table->index('company_id');
            $table->index('screen_id');
            $table->index('cluster_id');
        
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlist_items');
    }
};