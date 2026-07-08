<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            UPDATE report_queries
            SET sql_query = REPLACE(sql_query,
                'WHERE st.status = ''completed''',
                'WHERE st.status IN (''completed'', ''flagged'')')
            WHERE code = 'analytics_full_results_listing'
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE report_queries
            SET sql_query = REPLACE(sql_query,
                'WHERE st.status IN (''completed'', ''flagged'')',
                'WHERE st.status = ''completed''')
            WHERE code = 'analytics_full_results_listing'
        ");
    }
};
