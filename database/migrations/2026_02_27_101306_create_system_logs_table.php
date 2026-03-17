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
        Schema::create('system_logs', function (Blueprint $table) {

            $table->id();
        
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        
            $table->string('type')->index(); 
            // login, media_upload, screen_update, error, system
        
            $table->string('action');
        
            $table->text('description')->nullable();
        
            $table->json('meta')->nullable();
        
            $table->ipAddress('ip')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
