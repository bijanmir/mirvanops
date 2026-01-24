<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE maintenance_requests MODIFY COLUMN status VARCHAR(50) DEFAULT 'new'");
    }

    public function down(): void
    {
        // Leave as VARCHAR
    }
};