<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {

            $table->foreignId('company_id')
                  ->nullable()
                  ->after('id') // optional but clean
                  ->constrained('companies')
                  ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {

            // ✅ MUST DROP FK FIRST
            $table->dropForeign(['company_id']);

            // ✅ THEN DROP COLUMN
            $table->dropColumn('company_id');

        });
    }
};