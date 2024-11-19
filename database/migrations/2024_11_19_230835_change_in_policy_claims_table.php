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
        Schema::table('policy_claims', function (Blueprint $table) {
            $table->dateTime('claim_at')->nullable()->after('id');
            $table->dateTime('intimation_at')->nullable()->after('id');
            $table->string('survivor_name', 100)->nullable()->after('id');
            $table->string('contact_no', 100)->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_claims', function (Blueprint $table) {
            $table->dropColumn('claim_at');
            $table->dropColumn('intimation_at');
            $table->dropColumn('survivor_name');
            $table->dropColumn('contact_no');
        });
    }
};
