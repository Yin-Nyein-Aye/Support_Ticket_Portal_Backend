<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Flags for response SLA
            $table->boolean('response_notified_due_soon')->default(false);
            $table->boolean('response_notified_overdue')->default(false);

            // Flags for resolution SLA
            $table->boolean('resolution_notified_due_soon')->default(false);
            $table->boolean('resolution_notified_overdue')->default(false);

            // Optional: add response_status column if not exists
            $table->string('response_status', 20)->nullable()->after('sla_status');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'response_notified_due_soon',
                'response_notified_overdue',
                'resolution_notified_due_soon',
                'resolution_notified_overdue',
                'response_status',
            ]);
        });
    }
};
