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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->nullable();
            $table->foreignId('insurance_id')->nullable();
            $table->string('co_insurance')->nullable();
            $table->boolean('takeful_type')->nullable()->default(true);
            $table->string('policy_no')->nullable();
            $table->foreignId('agency_id')->nullable();
            $table->string('agency_code')->nullable();
            $table->foreignId('class_of_business_id')->nullable();
            $table->enum('orignal_endorsment', ['new', 'renewal'])->nullable()->default('new');
            $table->date('date_of_insurance')->nullable();
            $table->date('policy_start_period')->nullable();
            $table->date('policy_end_period')->nullable();
            $table->string('sum_insured')->nullable();
            $table->string('gross_premium')->nullable();
            $table->string('net_premium')->nullable();
            $table->string('cover_note_no')->nullable();
            $table->string('installment_plan')->nullable();
            $table->string('leader')->nullable();
            $table->string('leader_policy_no')->nullable();
            $table->string('branch')->nullable();
            $table->string('brokerage_amount')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('tax')->nullable();
            $table->string('percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
