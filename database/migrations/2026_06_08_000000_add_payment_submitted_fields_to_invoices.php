<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('payment_submitted_reference')->nullable()->after('payment_reference');
            $table->timestamp('payment_submitted_at')->nullable()->after('payment_submitted_reference');
            $table->foreignUuid('payment_submitted_by')
                  ->nullable()
                  ->after('payment_submitted_at')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_submitted_by');
            $table->dropColumn(['payment_submitted_reference', 'payment_submitted_at']);
        });
    }
};
