<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            if (!Schema::hasColumn('leases', 'company_id')) {
                $table->foreignId('company_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('leases', 'security_deposit')) {
                $table->decimal('security_deposit', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('leases', 'payment_due_day')) {
                $table->integer('payment_due_day')->default(1);
            }
            if (!Schema::hasColumn('leases', 'lease_type')) {
                $table->string('lease_type')->default('fixed');
            }
            if (!Schema::hasColumn('leases', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
