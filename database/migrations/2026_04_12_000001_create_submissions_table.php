<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SUBMISSIONS
 * Represents one completed Schedule 1 Application Form.
 * A client may have many submissions over time (1:N with clients).
 *
 * Fields mapped directly to Schedule 1 (KSTL Application Form):
 *   Company details         → pre-filled from clients table
 *   Testing method          → tests_requested (JSON)
 *   Sample type             → sample_type (fish, shellfish, seaweed, water, sediment, other)
 *   Sample transport method → transport_method (frozen, chilled, fresh)
 *   Common name             → sample_name
 *   Scientific name         → scientific_name
 *   Sampling date           → collected_at
 *   Quantity                → sample_quantity + sample_quantity_unit
 *   Date of application     → application_date
 *   Signature               → stored via service agreement (client already signed)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_number', 30)->unique();   // e.g. KSTL-2026-00042

            // ── Foreign keys ──────────────────────────────────────────
            $table->foreignUuid('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignUuid('received_by')->nullable()->constrained('users')->nullOnDelete();

            // ── Sample Information (Schedule 1: Sample table) ─────────
            $table->string('sample_name');                           // Common Name
            $table->string('scientific_name')->nullable();           // Scientific Name
            $table->text('sample_description')->nullable();          // Additional description
            $table->string('sample_type', 30);                       // Water, Fish, Shellfish, Seaweed, Sediment, Other
            $table->decimal('sample_quantity', 10, 3)->nullable();   // Quantity
            $table->string('sample_quantity_unit', 10)->nullable();  // g, kg, ml, L
            $table->date('collected_at')->nullable();                // Sampling Date
            $table->string('collection_location')->nullable();       // Where collected

            // ── Testing Method (Schedule 1: Chemical / Microbiology) ──
            $table->json('tests_requested')->nullable();             // Array of test keys
            $table->text('tests_other')->nullable();                 // Free-text other tests

            // ── Sample Transport Method (Schedule 1: Frozen/Chill/Fresh)
            $table->enum('transport_method', ['frozen', 'chilled', 'fresh'])->nullable();
            $table->string('transport_detail', 50)->nullable(); // e.g. air_freight_frozen, road_chilled_van

            // ── Priority & Instructions ───────────────────────────────
            $table->enum('priority', ['routine', 'urgent', 'emergency'])->default('routine');
            $table->text('special_instructions')->nullable();
            $table->date('results_required_by')->nullable();

            // ── Schedule 1 header fields ──────────────────────────────
            $table->enum('service_mode', ['lab_to_client', 'lab_to_lab'])->default('lab_to_client');
            $table->date('application_date')->nullable();            // Date of application

            // ── Declaration / Submitter ───────────────────────────────
            $table->string('submitter_name')->nullable();
         

            // ── Lab receipt ───────────────────────────────────────────
            $table->timestamp('submitted_at')->nullable();           // When client submitted online
            $table->timestamp('received_at')->nullable();            // When lab physically received

            // ── Workflow status ───────────────────────────────────────
            $table->enum('status', [
                'submitted',               // Client submitted, awaiting physical receipt
                'received',                // Lab logged the physical samples
                'assessing',               // Reception performing sample assessment
                'accepted',                // Passed assessment — moved to testing queue
                'rejected',                // Failed assessment
                'consent_to_proceed',      // Client consented to proceed despite rejection flag
                'testing',                 // Analyst performing tests
                'awaiting_authorisation',  // Tests complete, awaiting Director sign-off
                'authorised',              // Director authorised — result ready
                'completed',               // Invoice issued, result delivered to client
                'cancelled',               // Submission withdrawn
            ])->default('submitted');

            // ── Notes ─────────────────────────────────────────────────
            $table->text('client_notes')->nullable();                // Client notes on submission
            $table->text('lab_notes')->nullable();                   // Internal lab notes

            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id');
            $table->index('status');
            $table->index('received_by');
            $table->index('application_date');
            $table->index('collected_at');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};