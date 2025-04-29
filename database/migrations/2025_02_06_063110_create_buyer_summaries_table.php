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
        Schema::create('buyer_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('buyers');
            $table->double('total_transaction')->default(0);
            $table->double('total_payment')->default(0);
            $table->integer('month');
            $table->integer('fiscal_year');
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_summaries');
    }
};
