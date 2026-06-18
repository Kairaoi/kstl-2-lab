<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sample_tests', function (Blueprint $table) {
            $table->text('director_review_note')->nullable()->after('director_authorised_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sample_tests', function (Blueprint $table) {
            $table->dropColumn('director_review_note');
        });
    }
};
