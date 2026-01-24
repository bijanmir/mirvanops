<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            if (!Schema::hasColumn('leases', 'has_pet')) {
                $table->boolean('has_pet')->default(false);
            }
            if (!Schema::hasColumn('leases', 'pet_type')) {
                $table->string('pet_type')->nullable();
            }
            if (!Schema::hasColumn('leases', 'pet_deposit')) {
                $table->decimal('pet_deposit', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('leases', 'pet_rent')) {
                $table->decimal('pet_rent', 10, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->dropColumn(['has_pet', 'pet_type', 'pet_deposit', 'pet_rent']);
        });
    }
};
