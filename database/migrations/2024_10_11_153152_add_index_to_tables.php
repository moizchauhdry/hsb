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
        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('agencies', function (Blueprint $table) {
            $table->index('name');
            $table->index('code');
        });
        
        Schema::table('insurances', function (Blueprint $table) {
            $table->index('name');
        });
        
        Schema::table('business_classes', function (Blueprint $table) {
            $table->index('class_name');
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('name');
        });

        Schema::table('agencies', function (Blueprint $table) {
            $table->dropIndex('name');
            $table->dropIndex('code');
        });
        
        Schema::table('insurances', function (Blueprint $table) {
            $table->dropIndex('name');
        });
        
        Schema::table('business_classes', function (Blueprint $table) {
            $table->dropIndex('class_name');
            $table->dropIndex('department_id');
        });
    }
};
