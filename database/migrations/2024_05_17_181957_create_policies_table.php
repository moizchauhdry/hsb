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

            $table->string('policy_no')->nullable();
            $table->string('client_id')->nullable();
            $table->integer('insurer_id')->nullable();
            $table->integer('agency_id')->nullable();
            $table->integer('cob_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('user_id')->nullable();

            $table->enum('insurance_type', ['takaful', 'conventional'])->nullable();
            $table->enum('policy_type', ['new', 'renewal', 'endorsement', 'other'])->nullable();
            $table->enum('lead_type', ['direct', 'other', 'our'])->nullable();

            $table->integer('agency_code')->nullable();
            $table->string('child_agency_name', 100)->nullable();

            $table->string('leader_name', 250)->nullable();
            $table->string('leader_policy_no', 250)->nullable();
            $table->integer('installment_plan')->nullable();
            $table->string('base_doc_no', 100)->nullable();
            $table->string('branch', 100)->nullable();

            $table->date('date_of_issuance')->nullable();
            $table->date('policy_period_start')->nullable();
            $table->date('policy_period_end')->nullable();

            $table->double('sum_insured')->nullable();
            $table->double('gross_premium_100')->nullable();
            $table->double('gross_premium')->nullable();
            $table->double('net_premium_100')->nullable();
            $table->double('net_premium')->nullable();
            $table->double('outstanding_100')->nullable();

            $table->integer('receipt_no')->nullable();
            $table->date('receipt_date')->nullable();
            $table->double('receipt_amount')->nullable();

            $table->integer('gp_collected')->nullable();
            $table->double('rate_percentage')->nullable();
            $table->double('gross_premium_received')->nullable();
            $table->double('gross_premium_outstanding')->nullable();

            $table->double('brokerage_percentage')->nullable();
            $table->double('brokerage_amount')->nullable();
            $table->double('brokerage_received_amount')->nullable();
            $table->date('brokerage_paid_date')->nullable();
            $table->double('balance_amount')->nullable();

            $table->boolean('excel_import')->nullable()->default(false);
            $table->dateTime('excel_import_at')->nullable();

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
