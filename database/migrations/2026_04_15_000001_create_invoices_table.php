<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * INVOICES + INVOICE_ITEMS
 * Schedule 2 — Payment Form.
 *
 * invoices      — one invoice per submission (header)
 * invoice_items — one row per test billed (line items)
 *
 * price snapshots copied from sample_tests at generation time
 * so historical invoices remain accurate if prices change later.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Invoice header ────────────────────────────────────────────
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_number', 30)->unique();   // INV-2026-00001

            $table->foreignUuid('submission_id')
                  ->unique()
                  ->constrained('submissions')
                  ->cascadeOnDelete();

            $table->foreignUuid('result_id')
                  ->nullable()
                  ->constrained('results')
                  ->nullOnDelete();

            $table->foreignUuid('issued_by')
                  ->constrained('users');

            // ── Bill-to snapshot ──────────────────────────────────────
            $table->string('bill_to_company');
            $table->text('bill_to_address');
            $table->string('bill_to_phone', 30)->nullable();
            $table->string('bill_to_email')->nullable();

            // ── Amounts ───────────────────────────────────────────────
            $table->decimal('total_amount_aud', 10, 2)->default(0);

            // ── Dates ─────────────────────────────────────────────────
            $table->date('invoice_date');
            $table->date('payment_due_date');    // invoice_date + 10 working days

            // ── Payment ───────────────────────────────────────────────
            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'overdue',
                'waived',
            ])->default('unpaid');

            $table->string('payment_reference')->nullable();
            $table->timestamp('payment_received_at')->nullable();

            $table->foreignUuid('payment_verified_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('payment_status');
            $table->index('invoice_date');
        });

        // ── Invoice line items ────────────────────────────────────────
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('invoice_id')
                  ->constrained('invoices')
                  ->cascadeOnDelete();

            $table->foreignUuid('sample_test_id')
                  ->nullable()
                  ->constrained('sample_tests')
                  ->nullOnDelete();

            // ── Snapshot values ───────────────────────────────────────
            $table->string('item_description');          // e.g. 'E. coli — Water (Colilert)'
            $table->string('category', 50)->nullable();  // 'Microbiological' | 'Chemical'
            $table->decimal('unit_price_aud', 8, 2);
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('total_price_aud', 10, 2);

            $table->timestamps();

            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
