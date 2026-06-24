<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // ✅ ADD company_id
            $table->foreignId('company_id')
                  ->nullable()
                  ->after('id') // valid here (Schema::table)
                  ->constrained('companies')
                  ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // ✅ DROP FK + COLUMN
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');

        });
    }
};