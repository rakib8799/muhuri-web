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
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile_number')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('gender')->nullable();   // [male,female,others]
            $table->foreignId('division_id')->nullable()->default(null);
            $table->foreignId('district_id')->nullable()->default(null);
            $table->foreignId('upazila_id')->nullable()->default(null);
            $table->foreignId('union_id')->nullable()->default(null);
            $table->string('village')->nullable()->default(null);
            $table->date('joining_date')->nullable();
            $table->integer('monthly_salary')->default(0);
            $table->foreignId('departure_reason_id')->nullable()->constrained('hr_departure_reasons');
            $table->date('departure_date')->nullable();
            $table->text('departure_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_departed')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->string('note')->nullable()->default(null);
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
        Schema::dropIfExists('hr_employees');
    }
};
