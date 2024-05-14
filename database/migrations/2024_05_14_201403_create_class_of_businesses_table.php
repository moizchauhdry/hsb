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
        Schema::create('class_of_businesses', function (Blueprint $table) {
            $table->id();
            $table->string('b_class_name');
            $table->string('brokeage_rate_percentage')->nullable();
            $table->longText('insurance_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_of_businesses');
    }
};
