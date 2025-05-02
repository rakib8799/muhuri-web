<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_invoices', function (Blueprint $table) {
            $table->after('subscription_plan_id', function (Blueprint $table) {
                $table->foreignId('sms_purchase_id')->nullable()->constrained('sms_purchases')->onDelete('set null');
            });
        });
    }

    public function down(): void
    {
        Schema::table('payment_invoices', function (Blueprint $table) {
            $table->dropForeign(['sms_purchase_id']);
            $table->dropColumn('sms_purchase_id');
        });
    }
};

