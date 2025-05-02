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
        Schema::create('subscription_plan_subscription_plan_feature', function (Blueprint $table) {
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->name('fk_plan_id');
            $table->foreignId('subscription_plan_feature_id')->constrained('subscription_plan_features')->name('fk_feature_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plan_subscription_plan_feature');
    }
};
