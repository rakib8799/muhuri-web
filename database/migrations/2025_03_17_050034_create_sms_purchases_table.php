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
        Schema::create('sms_purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('sms_quantity');
            $table->decimal('sms_rate', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('payment_status')->default('pending'); // ['pending', 'paid', 'failed']
            $table->string('transaction_id')->nullable();
            $table->string('note')->nullable()->default(null);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_purchases');
    }
};
