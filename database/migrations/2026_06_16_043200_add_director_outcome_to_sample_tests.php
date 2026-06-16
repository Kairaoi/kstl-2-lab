<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sample_tests', function (Blueprint $table) {
            $table->string('director_outcome', 20)->nullable()->after('status');
            $table->timestamp('director_authorised_at')->nullable()->after('director_outcome');
            $table->foreignUuid('director_authorised_by')->nullable()->after('director_authorised_at')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sample_tests', function (Blueprint $table) {
            $table->dropForeign(['director_authorised_by']);
            $table->dropColumn(['director_outcome', 'director_authorised_at', 'director_authorised_by']);
        });
    }
};
