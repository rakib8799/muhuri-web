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
        Schema::create('purchase_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('item_unit_id')->nullable()->default(null)->constrained('item_units');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->string('unit_display_name')->nullable()->default(null);
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years');
            $table->double('total_box')->default(0);
            $table->double('total_poly')->default(0);
            $table->double('total_quantity')->default(0);
            $table->double('total_amount')->default(0);
            $table->date('purchase_date');
            $table->integer('month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_summaries');
    }
};
