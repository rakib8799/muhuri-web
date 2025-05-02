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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->string('invoice_number')->nullable();
            $table->foreignId('item_id')->constrained('items');
            $table->string('item_name');
            $table->double('amount');
            $table->foreignId('fiscal_year_id')->constrained();
            $table->integer('fiscal_year');
            $table->nullableMorphs('expenseable');
            $table->string('note')->nullable()->default(null);
            $table->boolean('is_editable')->default(true);
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
        Schema::dropIfExists('expenses');
    }
};
