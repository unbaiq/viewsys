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
        Schema::create('cluster_layouts', function (Blueprint $table) {

            $table->id();
        
            $table->foreignId('cluster_id')
                ->constrained()
                ->cascadeOnDelete();
        
            $table->string('zone_name');
        
            $table->integer('sort_order')->default(1);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cluster_layouts');
    }
};
