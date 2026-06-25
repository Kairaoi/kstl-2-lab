<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the composite index before changing the column type
        DB::statement('ALTER TABLE notifications DROP INDEX notifications_notifiable_type_notifiable_id_index');
        DB::statement('ALTER TABLE notifications MODIFY notifiable_id VARCHAR(36) NOT NULL');
        DB::statement('ALTER TABLE notifications ADD INDEX notifications_notifiable_type_notifiable_id_index (notifiable_type, notifiable_id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE notifications DROP INDEX notifications_notifiable_type_notifiable_id_index');
        DB::statement('ALTER TABLE notifications MODIFY notifiable_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE notifications ADD INDEX notifications_notifiable_type_notifiable_id_index (notifiable_type, notifiable_id)');
    }
};
