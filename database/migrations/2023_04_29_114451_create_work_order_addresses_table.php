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
        Schema::create('work_order_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wo_id')->unsigned();
            $table->enum('wo_addr_type', ['billing', 'shipping'])->default('billing');
            $table->string('wo_addr_customer_name', 100)->nullable();
            $table->string('wo_addr_str_address', 100)->nullable();
            $table->string('wo_addr_city', 100)->nullable();
            $table->string('wo_addr_state', 100)->nullable();
            $table->string('wo_addr_zipcode', 100)->nullable();
            $table->string('wo_addr_phone', 100)->nullable();
            $table->string('wo_addr_email', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_addresses');
    }
};
