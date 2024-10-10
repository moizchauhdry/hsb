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
            $table->double('gross_premium_received')->nullable()->default(0);
            $table->double('gross_premium_outstanding')->nullable()->default(0);
            $table->double('brokerage_amount')->nullable()->default(0);
            $table->double('brokerage_percentage')->nullable()->default(0);
            $table->double('brokerage_received_amount')->nullable()->default(0);
            $table->date('brokerage_paid_date')->nullable();
            $table->string('brokerage_status')->nullable();
            $table->double('balance_amount')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('gross_premium_received');
            $table->dropColumn('gross_premium_outstanding');
            $table->dropColumn('brokerage_amount');
            $table->dropColumn('brokerage_percentage');
            $table->dropColumn('brokerage_received_amount');
            $table->dropColumn('brokerage_paid_date');
            $table->dropColumn('brokerage_status');
            $table->dropColumn('balance_amount');
        });
    }
};
