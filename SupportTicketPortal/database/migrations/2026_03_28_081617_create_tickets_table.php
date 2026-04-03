<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 20)->unique();

            // Foreign keys
            $table->foreignId('priority_id')
                ->nullable()
                ->constrained('priorities')
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Ticket content
            $table->string('title', 255);
            $table->text('description')->nullable();

            // Status
            $table->string('status', 20)->default('pending');
            $table->string('sla_status', 50)->nullable();

            // SLA tracking
            $table->timestampTz('response_due_at')->nullable();
            $table->timestampTz('first_response_at')->nullable();
            $table->boolean('response_breached')->default(false);
            $table->timestampTz('response_breached_at')->nullable();

            $table->timestampTz('resolution_due_at')->nullable();
            $table->timestampTz('resolved_at')->nullable();
            $table->boolean('resolution_breached')->default(false);
            $table->timestampTz('resolution_breached_at')->nullable();

            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
