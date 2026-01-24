<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
            $table->date('move_in_date')->nullable();
            $table->date('move_out_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
            $table->index(['unit_id', 'status']);
            $table->index(['end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};