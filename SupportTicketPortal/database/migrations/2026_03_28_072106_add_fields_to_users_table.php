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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('organisation_id')
                ->nullable()
                ->after('id');

            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->cascadeOnDelete();

            // Split name into parts
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);

            // Optional avatar initials
            $table->string('avatar_initials', 3)->nullable();

            // Status flags
            $table->boolean('is_active')->default(false);
            $table->boolean('is_confirm')->default(false);

            // Login tracking
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organisation_id']);
            $table->dropColumn('organisation_id');

            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'avatar_initials',
                'is_active',
                'is_confirm',
                'last_login_at',
            ]);
        });
    }
};
