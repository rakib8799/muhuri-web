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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases');
            $table->foreignId('item_id')->constrained('items');
            $table->string('item_name');
            $table->foreignId('brand_id')->nullable()->default(null)->constrained('brands');
            $table->string('brand_name')->nullable()->default(null);
            $table->double('quantity')->default(1);
            $table->double('total_box')->default(0);
            $table->double('total_poly')->default(0);
            $table->double('mir')->default(0);
            $table->double('unit_price');
            $table->double('total_price')->default(0);
            $table->string('unit_name')->nullable()->default(null);
            $table->string('unit_value')->nullable()->default(null);
            $table->string('unit_display_name')->nullable()->default(null);
            $table->foreignId('item_unit_id')->nullable()->default(null)->constrained('item_units');
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
        Schema::dropIfExists('purchase_items');
    }
};
