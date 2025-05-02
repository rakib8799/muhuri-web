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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_number')->unique()->after('email_verified_at');
            $table->timestamp('mobile_number_verified_at')->nullable()->after('mobile_number');
            $table->string('otp')->nullable()->default(null)->after('mobile_number_verified_at');
            $table->timestamp('otp_expire_at')->nullable()->default(null)->after('otp');
            $table->timestamp('last_login_at')->nullable()->after('otp_expire_at');
            $table->boolean('is_active')->default(true)->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile_number');
            $table->dropColumn('mobile_number_verified_at');
            $table->dropColumn('otp');
            $table->dropColumn('otp_expire_at');
            $table->dropColumn('last_login_at');
            $table->dropColumn('is_active');
        });
    }
};
