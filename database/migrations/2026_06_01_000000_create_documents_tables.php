<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * DOCUMENTS REPOSITORY (Lab staff only)
 *
 * A "document" is a logical controlled record (e.g. "Microbiology APC SOP").
 * It has many versions; uploading a new file supersedes the previous version
 * rather than overwriting it, so prior files remain available for audit
 * (ISO 17025 document control).
 *
 * Both tables use UUID keys and foreignUuid to match users (uuid).
 * Files are stored on the 'private' disk — never publicly reachable by URL.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Logical document record ───────────────────────────────────
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');
            // Fixed category + optional free-text subcategory (e.g. "Microbiology").
            $table->enum('category', ['sop', 'manual', 'assessment_record', 'template']);
            $table->string('subcategory')->nullable();
            $table->string('reference_code', 60)->nullable();   // optional doc code, e.g. SOP-MICRO-001
            $table->text('description')->nullable();

            // Points at the version currently "in force". Nullable until first upload.
            $table->uuid('current_version_id')->nullable();

            $table->foreignUuid('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
        });

        // ── Versions (one row per uploaded file) ──────────────────────
        Schema::create('document_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('document_id')
                  ->constrained('documents')
                  ->cascadeOnDelete();

            $table->unsignedInteger('version_number');          // 1, 2, 3...
            $table->string('original_filename');
            $table->string('file_path');                        // path on 'private' disk
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->text('change_note')->nullable();            // "what changed" for this version

            $table->foreignUuid('uploaded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();

            $table->unique(['document_id', 'version_number']);
            $table->index('document_id');
        });

        // FK from documents.current_version_id → document_versions.id
        // (added after both tables exist to avoid a circular create-time dependency)
        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('current_version_id')
                  ->references('id')->on('document_versions')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['current_version_id']);
        });
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('documents');
    }
};
