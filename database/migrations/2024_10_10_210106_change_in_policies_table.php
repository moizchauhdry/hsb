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
            $table->index('policy_no');
            $table->index('client_id');
            $table->index('agency_id');
            $table->index('agency_code');
            $table->index('insurer_id');
            $table->index('cob_id');
            $table->index('user_id');
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropIndex(['policy_no']);
            $table->dropIndex(['client_id']);
            $table->dropIndex(['agency_id']);
            $table->dropIndex(['agency_code']);
            $table->dropIndex(['insurer_id']);
            $table->dropIndex(['cob_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['department_id']);
        });
    }
};
