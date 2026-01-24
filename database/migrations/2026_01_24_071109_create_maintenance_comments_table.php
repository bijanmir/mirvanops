<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->boolean('is_internal')->default(false); // internal notes vs visible to tenant
            $table->timestamps();

            $table->index(['maintenance_request_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_comments');
    }
};