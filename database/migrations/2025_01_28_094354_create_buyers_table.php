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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile_number')->unique();
            $table->double('previous_due')->nullable()->default(0);
            $table->foreignId('division_id')->nullable()->default(null);
            $table->foreignId('district_id')->nullable()->default(null);
            $table->foreignId('upazila_id')->nullable()->default(null);
            $table->foreignId('union_id')->nullable()->default(null);
            $table->string('village')->nullable()->default(null);
            $table->string('note')->nullable()->default(null);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_default')->default(0);
            $table->unsignedBigInteger('default_buyer_id')->nullable()->default(null);
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
        Schema::dropIfExists('buyers');
    }
};
