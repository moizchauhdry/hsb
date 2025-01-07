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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('policy_no', 100)->nullable();
            $table->integer('policy_id')->unsigned()->nullable();

            $table->double('sum_insured')->nullable();
            $table->double('net_premium')->nullable();
            $table->double('gross_premium')->nullable();
            $table->double('gross_premium_received')->nullable();
            $table->double('gross_premium_outstanding')->nullable();

            $table->double('brokerage_amount')->nullable();
            $table->double('brokerage_amount_received')->nullable();
            $table->double('brokerage_amount_outstanding')->nullable();
            $table->double('brokerage_amount_percentage')->nullable();
            $table->string('brokerage_amount_paid_at')->nullable();

            $table->double('gross_premium_100')->nullable();
            $table->double('net_premium_100')->nullable();

            $table->integer('receipt_no')->nullable();
            $table->double('receipt_amount')->nullable();
            $table->string('receipt_at')->nullable();

            $table->double('rate_percentage')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
