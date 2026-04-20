<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * RESULTS
 * One row per submission — the consolidated director-authorised report.
 * Uses UUID primary key and foreignUuid for submission_id and authorised_by
 * to match the UUID-based users and submissions tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ── Foreign keys (UUID) ───────────────────────────────────
            $table->foreignUuid('submission_id')
                  ->unique()
                  ->constrained('submissions')
                  ->cascadeOnDelete();

            $table->foreignUuid('authorised_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // ── Overall outcome ───────────────────────────────────────
            $table->enum('overall_outcome', ['pass', 'fail', 'inconclusive'])->nullable();
            $table->text('director_comments')->nullable();
            $table->text('result_query_notes')->nullable();

            // ── Authorisation ─────────────────────────────────────────
            $table->timestamp('authorised_at')->nullable();

            // ── Physical report ───────────────────────────────────────
            $table->string('signed_report_file')->nullable();
            $table->timestamp('report_uploaded_at')->nullable();

            // ── Delivery to client ────────────────────────────────────
            $table->timestamp('client_notified_at')->nullable();
            $table->timestamp('client_collected_at')->nullable();
            $table->string('collected_by_name')->nullable();

            $table->timestamps();

            $table->index('overall_outcome');
            $table->index('authorised_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
