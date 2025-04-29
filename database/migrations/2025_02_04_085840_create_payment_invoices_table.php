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
        Schema::create('payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans');
            $table->string('invoice_type')->default('subscription');
            $table->string('invoice_number')->unique();
            $table->double('amount');
            $table->string('currency_code')->default('BDT');
            $table->date('invoice_date');
            $table->date('expire_date')->nullable();
            $table->string('status')->default('pending'); // pending, expired
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->timestamp('paid_at')->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('payment_invoices');
    }
};
