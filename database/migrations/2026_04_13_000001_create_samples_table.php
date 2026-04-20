<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SAMPLES
 * Each row is one physical specimen within a submission.
 * A submission has one or more samples (1:N with submissions).
 *
 * sample_code: Lab-assigned on receipt (e.g. KSTL-S-00001).
 * FK to submissions uses UUID (cascadeOnDelete).
 * assessed_by / assigned_to reference users (UUID).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ── FK to submissions ─────────────────────────────────────
            $table->foreignUuid('submission_id')
                  ->constrained('submissions')
                  ->cascadeOnDelete();

            // ── Lab-assigned code ─────────────────────────────────────
            $table->string('sample_code', 30)->nullable()->unique(); // e.g. KSTL-S-00001

            // ── Schedule 1 sample table fields ───────────────────────
            $table->date('sampling_date');
            $table->string('common_name');                   // e.g. Yellowfin Tuna
            $table->string('scientific_name')->nullable();   // e.g. Thunnus albacares

            // ── Quantity ──────────────────────────────────────────────
            $table->decimal('quantity', 8, 2);
            $table->enum('quantity_unit', ['g', 'kg', 'ml', 'L', 'pcs'])->default('g');

            // ── Status (mirrors submission at sample level) ───────────
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'consent_to_proceed',
                'testing',
                'completed',
            ])->default('pending');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('submission_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
