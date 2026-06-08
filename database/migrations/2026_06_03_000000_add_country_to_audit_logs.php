<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add geolocation columns to audit_logs.
 *
 * The country is resolved ONCE at write time (in AuditService::log) from the
 * IP, using the local MaxMind GeoLite2 database — never looked up at report
 * render time. Historical rows therefore keep the country they were seen from.
 *
 * country_code is the ISO 3166-1 alpha-2 code (e.g. 'KI', 'AU', 'US').
 * Both are nullable: local/private IPs and unresolved lookups stay null.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('country_code', 2)->nullable()->after('ip_address');
            $table->string('country_name', 80)->nullable()->after('country_code');
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'country_name']);
        });
    }
};
