<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add director countersignature fields to clients table.
 * ...
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->text('director_signature_data')->nullable()->after('signature_captured_at');
            $table->string('director_signature_type', 20)->nullable()->after('director_signature_data');
            $table->string('director_signed_by')->nullable()->after('director_signature_type');
            $table->foreignUuid('director_signed_by_id')
                  ->nullable()
                  ->after('director_signed_by')
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('director_signed_at')->nullable()->after('director_signed_by_id');
            $table->string('director_signed_ip', 45)->nullable()->after('director_signed_at');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['director_signed_by_id']);
            $table->dropColumn([
                'director_signature_data',
                'director_signature_type',
                'director_signed_by',
                'director_signed_by_id',
                'director_signed_at',
                'director_signed_ip',
            ]);
        });
    }
};