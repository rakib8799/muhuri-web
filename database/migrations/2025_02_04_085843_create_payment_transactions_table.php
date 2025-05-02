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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('invoice_id')->nullable()->constrained('payment_invoices');
            $table->string('invoice_number')->nullable();
            $table->double('amount');
            $table->string('currency_code')->default('BDT');
            $table->string('payment_id')->nullable();
            $table->string('bkash_transaction_id')->nullable();
            $table->string('create_request_url')->nullable();
            $table->text('create_request')->nullable();
            $table->text('create_response')->nullable();
            $table->string('execute_request_url')->nullable();
            $table->text('execute_request')->nullable();
            $table->text('execute_response')->nullable();
            $table->string('status')->default('initiated'); // initiated, success, failed
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('payment_transactions');
    }
};
