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
        Schema::table('users', function (Blueprint $table) {

            // ✅ Assigned screen (for manager)
            $table->foreignId('screen_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // ✅ Drop foreign key first
            $table->dropForeign(['screen_id']);

            // ✅ Then drop column
            $table->dropColumn('screen_id');

        });
    }
};