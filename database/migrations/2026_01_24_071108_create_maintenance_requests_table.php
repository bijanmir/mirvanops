<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lease_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['plumbing', 'electrical', 'hvac', 'appliance', 'structural', 'pest', 'general', 'other'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'emergency'])->default('medium');
            $table->enum('status', ['new', 'assigned', 'in_progress', 'completed', 'closed', 'cancelled'])->default('new');
            $table->string('submitted_by_name')->nullable();
            $table->string('submitted_by_email')->nullable();
            $table->string('submitted_by_phone')->nullable();
            $table->boolean('permission_to_enter')->default(false);
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'priority']);
            $table->index(['unit_id', 'status']);
            $table->index(['vendor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};