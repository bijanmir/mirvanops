<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenance_requests', 'scheduled_date')) {
                $table->date('scheduled_date')->nullable();
            }
            if (!Schema::hasColumn('maintenance_requests', 'completed_date')) {
                $table->date('completed_date')->nullable();
            }
            if (!Schema::hasColumn('maintenance_requests', 'estimated_cost')) {
                $table->decimal('estimated_cost', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('maintenance_requests', 'actual_cost')) {
                $table->decimal('actual_cost', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('maintenance_requests', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('maintenance_requests', 'reported_by')) {
                $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('maintenance_requests', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropColumn(['scheduled_date', 'completed_date', 'estimated_cost', 'actual_cost', 'notes', 'reported_by', 'assigned_to']);
        });
    }
};