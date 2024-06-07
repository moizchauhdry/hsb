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
        Schema::create('policy_installment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->date('due_date')->nullable();
            $table->decimal('net_premium',8,2)->nullable();
            $table->decimal('gross_premium',8,2)->nullable();
            $table->enum('payment_status', ['pending', 'paid','un-paid'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_installment_plans');
    }
};
