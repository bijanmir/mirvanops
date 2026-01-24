<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'hourly_rate')) {
                $table->decimal('hourly_rate', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('vendors', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'zip')) {
                $table->string('zip')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        // 
    }
};