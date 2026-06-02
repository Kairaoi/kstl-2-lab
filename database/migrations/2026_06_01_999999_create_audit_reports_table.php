<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * REPORTING SUBSYSTEM — report_queries + report_executions
 *
 * Creates the base tables for the Audit/Reporting subsystem.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Report definitions ────────────────────────────────────────
        Schema::create('report_queries', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Scoped by Spatie role: array of role names allowed to see/run this
            // report. Null/empty = visible to any authenticated user.
            $table->json('allowed_roles')->nullable();
            $table->string('code')->unique();        // e.g. 'audit_failed_logins'
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('sql_query');
            $table->string('category', 60)->default('general');
            $table->json('parameters')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('category');
            $table->index('is_active');
        });

        // ── Execution log ─────────────────────────────────────────────
        Schema::create('report_executions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('report_query_id')
                  ->constrained('report_queries')
                  ->cascadeOnDelete();

            $table->foreignUuid('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->json('parameters')->nullable();
            $table->unsignedInteger('result_count')->default(0);
            $table->decimal('execution_time_ms', 10, 2)->default(0);
            $table->timestamp('executed_at')->nullable();

            $table->timestamps();

            $table->index('report_query_id');
            $table->index('user_id');
            $table->index('executed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_executions');
        Schema::dropIfExists('report_queries');
    }
};