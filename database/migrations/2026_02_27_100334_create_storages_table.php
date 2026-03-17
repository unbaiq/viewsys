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
        Schema::create('storages', function (Blueprint $table) {

            $table->id();
        
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();
        
            $table->bigInteger('used')->default(0); // in MB
            $table->bigInteger('limit')->default(10240);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storages');
    }
};
