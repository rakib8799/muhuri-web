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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('central_id')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('brands');
    }
};
