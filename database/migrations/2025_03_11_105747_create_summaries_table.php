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
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->date('summary_date');
            $table->double('total_purchase')->default(0);
            $table->double('total_sale')->default(0);
            $table->double('total_expense')->default(0);
            $table->double('total_buyer_payment')->default(0);
            $table->double('total_supplier_payment')->default(0);
            $table->integer('fiscal_year');
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};
