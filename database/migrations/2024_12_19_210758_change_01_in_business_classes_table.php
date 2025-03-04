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
        Schema::table('business_classes', function (Blueprint $table) {
            $table->integer('group_id')->unsigned()->nullable()->after('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_classes', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
};
