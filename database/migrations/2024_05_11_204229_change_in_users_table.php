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
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->string('cnic_name')->nullable()->after('address');
            $table->string('cnic_no')->nullable()->after('cnic_name');
            $table->date('cnic_expiry_date')->nullable()->after('cnic_no');
            $table->string('father_name')->nullable()->after('cnic_expiry_date');
            $table->enum('gender', ['male', 'female','others'])->after('father_name')->nullable()->default('male');
            $table->date('dob')->nullable()->after('gender');
            $table->enum('type', ['individual', 'business'])->after('dob')->nullable()->default('individual');
            $table->string('designation')->nullable()->after('type');
            $table->string('qualification')->nullable()->after('designation');

            $table->integer('code')->unsigned()->unique()->nullable()->after('name');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('cnic_name');
            $table->dropColumn('cnic_no');
            $table->dropColumn('cnic_expiry_date');
            $table->dropColumn('father_name');
            $table->dropColumn('gender');
            $table->dropColumn('dob');
            $table->dropColumn('type');
            $table->dropColumn('designation');
            $table->dropColumn('qualification');

            $table->dropIndex(['code']);
            $table->dropColumn('code');
        });
    }
};
