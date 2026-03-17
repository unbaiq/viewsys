<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cluster_screen', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cluster_id')
                ->constrained('clusters')
                ->cascadeOnDelete();

            $table->foreignId('screen_id')
                ->constrained('screens')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['cluster_id','screen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cluster_screen');
    }
};