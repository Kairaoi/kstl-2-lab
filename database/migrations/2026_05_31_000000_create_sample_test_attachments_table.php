<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SAMPLE_TEST_ATTACHMENTS
 * Supporting files/documents an analyst attaches to a single test
 * (raw instrument output, chromatograms, photos, method records, etc.).
 *
 * One sample_test has many attachments (1:N).
 * Files are stored on the 'private' disk; only the path is kept here.
 *
 * Both FKs are UUID to match the UUID-keyed users and sample_tests tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sample_test_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ── FK to the test (UUID, cascade on delete) ──────────────
            $table->foreignUuid('sample_test_id')
                  ->constrained('sample_tests')
                  ->cascadeOnDelete();

            // ── Who uploaded it (UUID — users.id is uuid) ─────────────
            $table->foreignUuid('uploaded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // ── File metadata ─────────────────────────────────────────
            $table->string('original_filename');           // As uploaded by the analyst
            $table->string('file_path');                   // Storage path on the 'private' disk
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();   // bytes
            $table->string('description')->nullable();     // Optional analyst note

            $table->timestamps();

            $table->index('sample_test_id');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_test_attachments');
    }
};
