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
        Schema::create('policy_excels', function (Blueprint $table) {
            $table->id();
            $table->string('insurer_name', 250)->nullable();
            $table->string('insurer_code', 250)->nullable();
            $table->string('client_name', 250)->nullable();
            $table->string('client_code', 250)->nullable();
            $table->string('policy_no', 250)->nullable();
            $table->string('agency_code', 250)->nullable();
            $table->string('department_name', 250)->nullable();
            $table->string('department_code', 250)->nullable();
            $table->string('cob_name', 250)->nullable();
            $table->string('cob_code', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_excels');
    }
};
