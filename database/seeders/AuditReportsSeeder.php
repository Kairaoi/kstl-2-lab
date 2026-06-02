<?php

namespace Database\Seeders;

use App\Models\ReportQuery;
use Illuminate\Database\Seeder;

/**
 * Audit & Security Reports Seeder
 */
class AuditReportsSeeder extends Seeder
{
    private const AUDIT_ROLES = ['director', 'auditor', 'admin', 'super_admin'];

    public function run(): void
    {
        $reports = [
            [
                'code'        => 'audit_full_log',
                'name'        => 'Audit Trail — Full Log',
                'description' => 'Complete audit trail: all actions with user, event, description and IP. Last 5,000 entries.',
                'category'    => 'audit',
                'sort_order'  => 1,
                'sql_query'   => <<<SQL
                    SELECT 
                        created_at AS occurred_at,
                        event,
                        user_name,
                        description,
                        ip_address,
                        user_agent,
                        auditable_type
                    FROM audit_logs 
                    ORDER BY created_at DESC 
                    LIMIT 5000
                SQL,
            ],

            [
                'code'        => 'audit_failed_logins',
                'name'        => 'Security — Failed Login Attempts',
                'description' => 'All failed login attempts with attempted email and source IP.',
                'category'    => 'security',
                'sort_order'  => 2,
                'sql_query'   => <<<SQL
                    SELECT 
                        created_at AS attempted_at,
                        user_name AS attempted_email,
                        ip_address,
                        user_agent,
                        description
                    FROM audit_logs 
                    WHERE event = 'login_failed'
                    ORDER BY created_at DESC 
                    LIMIT 5000
                SQL,
            ],

            [
                'code'        => 'audit_successful_logins',
                'name'        => 'Security — Successful Logins',
                'description' => 'All successful login events with user and IP address.',
                'category'    => 'security',
                'sort_order'  => 3,
                'sql_query'   => <<<SQL
                    SELECT 
                        created_at AS login_at,
                        user_name,
                        ip_address,
                        user_agent
                    FROM audit_logs 
                    WHERE event = 'login'
                    ORDER BY created_at DESC 
                    LIMIT 5000
                SQL,
            ],

            [
                'code'        => 'audit_login_activity',
                'name'        => 'Security — Login Activity (Logins + Logouts)',
                'description' => 'Combined view of successful logins and logouts.',
                'category'    => 'security',
                'sort_order'  => 4,
                'sql_query'   => <<<SQL
                    SELECT 
                        created_at AS occurred_at,
                        event,
                        user_name,
                        ip_address,
                        user_agent
                    FROM audit_logs 
                    WHERE event IN ('login', 'logout')
                    ORDER BY created_at DESC 
                    LIMIT 5000
                SQL,
            ],

            [
                'code'        => 'audit_activity_by_user',
                'name'        => 'Audit — Activity Summary by User',
                'description' => 'Summary of actions per user including logins, failed attempts, and authorisations.',
                'category'    => 'audit',
                'sort_order'  => 5,
                'sql_query'   => <<<SQL
                    SELECT 
                        user_name,
                        COUNT(*) AS total_actions,
                        SUM(event = 'login') AS successful_logins,
                        SUM(event = 'logout') AS logouts,
                        SUM(event = 'login_failed') AS failed_logins,
                        SUM(event = 'authorised') AS authorisations,
                        MAX(created_at) AS last_action_at
                    FROM audit_logs 
                    GROUP BY user_name
                    ORDER BY total_actions DESC 
                    LIMIT 1000
                SQL,
            ],

            [
                'code'        => 'audit_authorisations',
                'name'        => 'Audit — Result Authorisations',
                'description' => 'All result authorisations by Director (for ISO 17025 traceability).',
                'category'    => 'audit',
                'sort_order'  => 6,
                'sql_query'   => <<<SQL
                    SELECT 
                        created_at AS authorised_at,
                        user_name AS authorised_by,
                        description,
                        ip_address
                    FROM audit_logs 
                    WHERE event = 'authorised'
                    ORDER BY created_at DESC 
                    LIMIT 5000
                SQL,
            ],
        ];

        foreach ($reports as $r) {
            ReportQuery::updateOrCreate(
                ['code' => $r['code']],
                [
                    'name'          => $r['name'],
                    'description'   => $r['description'],
                    'category'      => $r['category'],
                    'sql_query'     => $r['sql_query'],
                    'allowed_roles' => self::AUDIT_ROLES,
                    'parameters'    => null,
                    'is_active'     => true,
                    'sort_order'    => $r['sort_order'],
                ]
            );
        }
    }
}