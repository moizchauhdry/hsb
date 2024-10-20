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
            $table->string('leader_name', 250)->nullable();
            $table->double('rate_percentage')->nullable();
            $table->double('gross_premium_100')->nullable();
            $table->double('net_premium_100')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('leader_name');
            $table->dropColumn('rate_percentage');
            $table->dropColumn('gross_premium_100');
            $table->dropColumn('net_premium_100');
        });
    }
};
