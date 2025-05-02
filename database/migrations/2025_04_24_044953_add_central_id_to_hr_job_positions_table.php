<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('hr_job_positions', function (Blueprint $table) {
            $table->unsignedBigInteger('central_id')->nullable()->after('id'); // adjust 'after' if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('hr_job_positions', function (Blueprint $table) {
            $table->dropColumn('central_id');
        });
    }
};
