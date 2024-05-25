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
        Schema::create('policy_claim_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->foreignId('policy_claim_id')->constrained('policy_claims')->onDelete('cascade');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_claim_notes');
    }
};
