<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('documentable_type'); // Property, Unit, Tenant, Lease
            $table->unsignedBigInteger('documentable_id');
            $table->string('name');
            $table->string('original_filename');
            $table->string('filename');
            $table->string('path');
            $table->string('mime_type');
            $table->unsignedBigInteger('size'); // bytes
            $table->string('category')->nullable(); // lease, id, insurance, inspection, other
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['documentable_type', 'documentable_id']);
            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};