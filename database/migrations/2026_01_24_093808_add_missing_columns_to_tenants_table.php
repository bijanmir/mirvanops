<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'alternate_phone')) {
                $table->string('alternate_phone')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'ssn_last_four')) {
                $table->string('ssn_last_four', 4)->nullable();
            }
            if (!Schema::hasColumn('tenants', 'drivers_license')) {
                $table->string('drivers_license')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'employer')) {
                $table->string('employer')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'employer_phone')) {
                $table->string('employer_phone')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'annual_income')) {
                $table->decimal('annual_income', 12, 2)->nullable();
            }
            if (!Schema::hasColumn('tenants', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'status')) {
                $table->string('status')->default('pending');
            }
        });
    }

    public function down(): void
    {
        //
    }
};