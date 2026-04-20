<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            // ── Company / organisation details ────────────────────────
            $table->string('company_name');
            $table->text('address');
            $table->string('company_phone', 30)->nullable();

            // ── Responsible officer ───────────────────────────────────
            // Name is derived from the authenticated user (users.first_name + last_name).
            // Email and phone are taken from the user account — not stored separately.
            // This field is kept for denormalised display on PDFs/reports after the
            // user name may have changed.
            $table->string('responsible_officer_name')->nullable();

            // ── Service agreement ─────────────────────────────────────
            $table->timestamp('service_agreement_signed_at')->nullable();
            $table->string('service_agreement_file')->nullable();

            // ── Digital Signature Fields ──────────────────────────────
            $table->longText('signature_data')->nullable();
            $table->string('signature_type', 20)->nullable();
            $table->timestamp('signature_captured_at')->nullable();

            // ── Notes (internal lab use) ──────────────────────────────
            $table->text('internal_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};