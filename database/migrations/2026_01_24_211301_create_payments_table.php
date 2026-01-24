<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lease_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method');
            $table->string('period_covered');
            $table->string('reference_number')->nullable();
            $table->decimal('late_fee', 10, 2)->nullable();
            $table->enum('status', ['completed', 'pending', 'failed', 'refunded'])->default('completed');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['company_id', 'payment_date']);
            $table->index(['lease_id', 'period_covered']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};