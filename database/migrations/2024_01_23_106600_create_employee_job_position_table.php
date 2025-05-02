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
        Schema::create('hr_employee_hr_job_position', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('hr_employees');
            $table->foreignId('job_position_id')->constrained('hr_job_positions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_employee_hr_job_position');
    }
};
