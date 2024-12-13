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
            $table->index('id');
        });

        Schema::table('policy_claims', function (Blueprint $table) {
            $table->index('policy_id');
        });

        Schema::table('business_classes', function (Blueprint $table) {
            $table->index('id');
        });

        Schema::table('insurances', function (Blueprint $table) {
            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropIndex(['id']);
        });

        Schema::table('policy_claims', function (Blueprint $table) {
            $table->dropIndex(['policy_id']);
        });

        Schema::table('business_classes', function (Blueprint $table) {
            $table->dropIndex(['id']);
        });

        Schema::table('insurances', function (Blueprint $table) {
            $table->dropIndex(['id']);
        });
    }
};
