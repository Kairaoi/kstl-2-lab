<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ── Complainant ───────────────────────────────────────────
            $table->foreignUuid('complainant_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('complainant_name')->nullable();
            $table->string('complainant_contact', 50)->nullable();
            $table->string('complainant_email')->nullable();
            $table->string('complainant_organisation')->nullable();

            // ── Incident details ──────────────────────────────────────
            $table->date('incident_date');
            $table->string('subject');
            $table->json('complaint_types');
            $table->string('other_complaint_type')->nullable();
            $table->text('description');

            // ── Link to submission (optional) ─────────────────────────
            $table->foreignUuid('submission_id')
                  ->nullable()
                  ->constrained('submissions')
                  ->nullOnDelete();

            // ── Lab internal responses ────────────────────────────────
            $table->foreignUuid('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->text('lab_response')->nullable();
            $table->text('action_taken')->nullable();

            $table->foreignUuid('resolved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('resolved_at')->nullable();

            $table->enum('status', [
                'open',
                'under_investigation',
                'resolved',
                'closed',
            ])->default('open');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('complainant_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
