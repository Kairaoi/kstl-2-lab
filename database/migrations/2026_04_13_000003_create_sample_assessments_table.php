<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SAMPLE_ASSESSMENTS
 * One row per sample — records reception's assessment outcome.
 * Separated from samples (2NF) because assessment data only exists
 * after reception staff review the physical sample on arrival.
 *
 * 7 Assessment Criteria (from Lab Handbook §2.5):
 *   temperature  — received at correct temp for declared transport method
 *   storage      — properly stored before submission
 *   transport    — method matches what client declared
 *   packaging    — sealed, intact, properly labelled
 *   colour       — no discolouration indicating spoilage
 *   odour        — no off-odours
 *   weight       — quantity within acceptable range (200g–500g minimum)
 *
 * Each criterion: boolean pass/fail + free-text observation.
 *
 * outcome:
 *   accepted          — all criteria passed
 *   accepted_with_note — minor concerns but accepted
 *   rejected          — one or more criteria failed
 *
 * client_decision: recorded by reception when client is contacted
 *   after rejection.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sample_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ── FK to sample (1:1) ────────────────────────────────────
            $table->foreignUuid('sample_id')
                  ->unique()
                  ->constrained('samples')
                  ->cascadeOnDelete();

            // ── Who assessed ──────────────────────────────────────────
            $table->foreignUuid('assessed_by')
                  ->constrained('users');

            $table->timestamp('assessed_at');

            // ── 7 Assessment Criteria ─────────────────────────────────

            $table->boolean('temperature_ok')->nullable();
            $table->text('temperature_notes')->nullable();

            $table->boolean('storage_ok')->nullable();
            $table->text('storage_notes')->nullable();

            $table->boolean('transport_ok')->nullable();
            $table->text('transport_notes')->nullable();

            $table->boolean('packaging_ok')->nullable();
            $table->text('packaging_notes')->nullable();

            $table->boolean('colour_ok')->nullable();
            $table->text('colour_notes')->nullable();

            $table->boolean('odour_ok')->nullable();
            $table->text('odour_notes')->nullable();

            $table->boolean('weight_ok')->nullable();
            $table->text('weight_notes')->nullable();

            $table->text('additional_observations')->nullable();

            // ── Outcome ───────────────────────────────────────────────
            $table->enum('outcome', [
                'accepted',
                'accepted_with_note',
                'rejected',
            ])->nullable();

            // ── Rejection / client consent flow ───────────────────────
            $table->text('rejection_reason')->nullable();     // Summary sent to client

            $table->enum('client_decision', [
                'confirm_rejection',   // Client accepted rejection; no testing
                'consent_to_proceed',  // Client consented to proceed despite issues
            ])->nullable();

             $table->string('consent_token', 64)->nullable()->unique();
            $table->timestamp('consent_notified_at')->nullable();
            $table->timestamp('consent_token_expires_at')->nullable();
            $table->enum('consent_method', ['system', 'manual'])->nullable();

            $table->timestamp('client_decision_at')->nullable();

            $table->foreignUuid('client_decision_recorded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();

            $table->index('assessed_by');
            $table->index('outcome');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_assessments');
    }
};
