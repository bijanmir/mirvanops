<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Helper to check if index exists
        $indexExists = function ($table, $indexName) {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return count($indexes) > 0;
        };

        // Leases indexes
        if (!$indexExists('leases', 'leases_company_id_status_index')) {
            Schema::table('leases', function (Blueprint $table) {
                $table->index(['company_id', 'status']);
            });
        }

        if (!$indexExists('leases', 'leases_company_id_end_date_index')) {
            Schema::table('leases', function (Blueprint $table) {
                $table->index(['company_id', 'end_date']);
            });
        }

        // Tenants indexes
        if (!$indexExists('tenants', 'tenants_company_id_status_index')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->index(['company_id', 'status']);
            });
        }

        // Units indexes
        if (!$indexExists('units', 'units_company_id_status_index')) {
            Schema::table('units', function (Blueprint $table) {
                $table->index(['company_id', 'status']);
            });
        }

        // Maintenance requests indexes
        if (!$indexExists('maintenance_requests', 'maintenance_requests_company_id_status_index')) {
            Schema::table('maintenance_requests', function (Blueprint $table) {
                $table->index(['company_id', 'status']);
            });
        }

        if (!$indexExists('maintenance_requests', 'maintenance_requests_company_id_priority_index')) {
            Schema::table('maintenance_requests', function (Blueprint $table) {
                $table->index(['company_id', 'priority']);
            });
        }

        // Activity logs index
        if (!$indexExists('activity_logs', 'activity_logs_company_id_created_at_index')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->index(['company_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'status']);
            $table->dropIndex(['company_id', 'end_date']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'status']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'status']);
        });

        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'status']);
            $table->dropIndex(['company_id', 'priority']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'created_at']);
        });
    }
};