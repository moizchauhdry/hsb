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
        Schema::table('batch_errors', function (Blueprint $table) {
            $table->string('policy_no', 100)->nullable()->after('batch_id');
            $table->json('multiple_errors')->nullable()->after('error_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_errors', function (Blueprint $table) {
            $table->dropColumn('policy_no');
            $table->dropColumn('multiple_errors');
        });
    }
};
