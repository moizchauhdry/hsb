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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('wo_sr_no', 100)->nullable()->after('wo_bike_color');
            $table->string('wo_po_no', 100)->nullable()->after('wo_bike_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('wo_sr_no');
            $table->dropColumn('wo_po_no');
        });
    }
};
