<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('companies', 'subscription_id')) {
                $table->string('subscription_id')->nullable()->after('stripe_id');
            }
            if (!Schema::hasColumn('companies', 'plan')) {
                $table->string('plan')->default('free')->after('subscription_id');
            }
            if (!Schema::hasColumn('companies', 'subscription_status')) {
                $table->string('subscription_status')->default('active')->after('plan');
            }
            if (!Schema::hasColumn('companies', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('trial_ends_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['subscription_id', 'plan', 'subscription_status', 'subscription_ends_at']);
        });
    }
};
