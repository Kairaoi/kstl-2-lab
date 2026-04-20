<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AUDIT_LOGS — Immutable append-only record of every significant action.
 * Required by ISO 17025 for traceability.
 * This table must NEVER be updated or deleted.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ── Who ───────────────────────────────────────────────────
            $table->foreignUuid('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('user_name')->nullable();   // Snapshot — preserved if user deleted

            // ── What ──────────────────────────────────────────────────
            $table->string('event', 60);               // created, updated, deleted, signed, authorised...
            $table->string('description')->nullable(); // Human-readable summary

            // ── Which entity ──────────────────────────────────────────
            $table->uuidMorphs('auditable');           // auditable_type + auditable_id

            // ── Data snapshot ─────────────────────────────────────────
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // ── Request context ───────────────────────────────────────
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            // Append-only — no updated_at
            $table->timestamp('created_at');

            $table->index('user_id');
            $table->index('event');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};