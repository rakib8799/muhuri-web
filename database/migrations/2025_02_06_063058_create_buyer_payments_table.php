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
        Schema::create('buyer_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('buyers');
            $table->double('amount');
            $table->date('payment_date');
            $table->string('payment_method')->default('cash');
            $table->string('invoice_number')->unique();
            $table->integer('fiscal_year');
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years');
            $table->string('note')->nullable()->default(null);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_payments');
    }
};
