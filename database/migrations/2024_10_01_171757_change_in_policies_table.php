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
        Schema::table('policies', function (Blueprint $table) {
            $table->string('child_agency_name', 100)->nullable()->after('agency_code');
            // $table->renameColumn('insurance_id', 'insurer_id');
            $table->boolean('excel_import')->nullable()->default(false);
            $table->dateTime('excel_import_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('child_agency_name');
            $table->dropColumn('excel_import');
            $table->dropColumn('excel_import_at');
        });
    }
};
