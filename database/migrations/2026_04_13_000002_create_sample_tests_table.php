<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SAMPLE_TESTS
 * Junction table — one row per test assigned to a sample.
 *
 * test_key: matches the checkbox values from the create blade
 *   e.g. 'total_coliforms', 'e_coli', 'histamine', 'moisture'
 *
 * price_aud_snapshot: copied from the test price at time of submission
 *   so historical invoices remain accurate even if prices change.
 *
 * assigned_to: analyst UUID responsible for this specific test.
 *
 * result_qualifier: pass/fail/detected/not_detected/</>
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sample_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ── FK to samples ─────────────────────────────────────────
            $table->foreignUuid('sample_id')
                  ->constrained('samples')
                  ->cascadeOnDelete();

            // ── Test identity ─────────────────────────────────────────
            // Stored as key string matching the create blade checkboxes
            $table->string('test_key', 50);                  // e.g. 'e_coli', 'histamine'
            $table->string('test_label')->nullable();         // Snapshot: 'E. coli', 'Histamine — Rapid Kit'
            $table->string('test_category', 30)->nullable();  // 'microbiological' | 'chemical'

            // ── Analyst assigned ──────────────────────────────────────
            $table->foreignUuid('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // ── Price snapshot ────────────────────────────────────────
            $table->decimal('price_aud_snapshot', 8, 2)->nullable();

            // ── Result ────────────────────────────────────────────────
            $table->string('result_value')->nullable();       // e.g. '12.4', 'Detected', 'Pass'
            $table->string('result_unit', 30)->nullable();    // e.g. 'mg/kg', 'CFU/g'
            $table->enum('result_qualifier', [
                'detected',
                'not_detected',
                'pass',
                'fail',
                'less_than',
                'greater_than',
                'equal_to',
                'pending',
            ])->default('pending');
            $table->text('result_notes')->nullable();
            $table->string('result_file')->nullable();        // Storage path to raw data

            // ── Timing ────────────────────────────────────────────────
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // ── Status ────────────────────────────────────────────────
            $table->enum('status', [
                'queued',       // Waiting for analyst
                'in_progress',  // Analyst started
                'completed',    // Result entered
                'flagged',      // Flagged for Director review
            ])->default('queued');

            $table->timestamps();

            $table->unique(['sample_id', 'test_key']); // Prevent duplicate test per sample
            $table->index('assigned_to');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_tests');
    }
};
