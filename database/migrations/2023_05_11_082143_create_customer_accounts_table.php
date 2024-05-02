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
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_type', 100);
            $table->string('name', 100);
            $table->string('phone_home', 100)->nullable();
            $table->string('phone_mobile', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 100)->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->json('products')->nullable();
            $table->json('services')->nullable();
            $table->boolean('motor_warranty')->default(false);
            $table->boolean('frame_warranty')->default(false);
            $table->boolean('electronics_warranty')->default(false);
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_accounts');
    }
};
