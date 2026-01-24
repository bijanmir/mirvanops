<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('unit_number');
            $table->decimal('beds', 3, 1)->nullable();
            $table->decimal('baths', 3, 1)->nullable();
            $table->integer('sqft')->nullable();
            $table->decimal('market_rent', 10, 2)->nullable();
            $table->enum('status', ['vacant', 'occupied', 'maintenance', 'offline'])->default('vacant');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
            $table->index(['property_id', 'unit_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};