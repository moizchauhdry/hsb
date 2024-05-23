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
        Schema::create('policy_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->nullable()->constrained('policies')->onDelete('cascade');
            $table->longText('detail')->nullable();
            $table->string('progress')->nullable();
            $table->longText('settled')->nullable();
            $table->enum('status', ['open', 'close'])->nullable()->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_claims');
    }
};
