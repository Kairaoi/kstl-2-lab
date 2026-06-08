<?php

namespace Database\Seeders;

use App\Models\ReportQuery;
use Illuminate\Database\Seeder;

/**
 * Audit & Compliance Reports Seeder
 *
 * Two report families:
 *   1. Audit-log reports — query the audit_logs trail (login/activity/etc.).
 *   2. Operational/compliance reports — query the domain tables directly
 *      (submissions, results, sample_tests, sample_assessments, invoices,
 *      complaints) to answer the questions an ISO 17025 auditor actually asks:
 *      turnaround, traceability, rejections, analyst workload, records control.
 *
 * All reports are role-scoped via allowed_roles and read-only (SELECT only),
 * parameterless, and bounded by LIMIT so they run safely from a plain "View".
 */
class AuditReportsSeeder extends Seeder
{
    // Audit trail & security reports — auditors only (directors are not auditing themselves)
    private const AUDITOR_ROLES    = ['auditor', 'admin', 'super_admin'];
    // Operational/compliance reports — directors also need visibility for lab management
    private const COMPLIANCE_ROLES = ['director', 'auditor', 'admin', 'super_admin'];

    public function run(): void
    {
        $reports = array_merge(
            $this->auditLogReports(),
            $this->complianceReports(),
        );

        foreach ($reports as $r) {
            $roles = in_array($r['category'], ['audit', 'security'])
                ? self::AUDITOR_ROLES
                : self::COMPLIANCE_ROLES;

            ReportQuery::updateOrCreate(
                ['code' => $r['code']],
                [
                    'name'          => $r['name'],
                    'description'   => $r['description'],
                    'category'      => $r['category'],
                    'sql_query'     => $r['sql_query'],
                    'allowed_roles' => $roles,
                    'parameters'    => null,
                    'is_active'     => true,
                    'sort_order'    => $r['sort_order'],
                ]
            );
        }
    }

    // ── 1. Audit-log reports (the trail) ───────────────────────────
    private function auditLogReports(): array
    {
        return [
            [
                'code' => 'audit_full_log', 'name' => 'Audit Trail — Full Log',
                'description' => 'Complete audit trail: all actions with user, event, description and IP. Last 5,000 entries.',
                'category' => 'audit', 'sort_order' => 1,
                'sql_query' => <<<SQL
                    SELECT created_at AS occurred_at, event, user_name, description, ip_address, auditable_type
                    FROM audit_logs
                    ORDER BY created_at DESC
                    LIMIT 5000
                SQL,
            ],
            [
                'code' => 'audit_failed_logins', 'name' => 'Security — Failed Login Attempts',
                'description' => 'All failed login attempts with attempted email and source IP.',
                'category' => 'security', 'sort_order' => 2,
                'sql_query' => <<<SQL
                    SELECT created_at AS attempted_at, user_name AS attempted_email, ip_address, user_agent
                    FROM audit_logs
                    WHERE event = 'login_failed'
                    ORDER BY created_at DESC
                    LIMIT 5000
                SQL,
            ],
            [
                'code' => 'audit_login_activity', 'name' => 'Security — Login Activity',
                'description' => 'Successful logins and logouts by user, with IP.',
                'category' => 'security', 'sort_order' => 3,
                'sql_query' => <<<SQL
                    SELECT created_at AS occurred_at, event, user_name, ip_address
                    FROM audit_logs
                    WHERE event IN ('login', 'logout')
                    ORDER BY created_at DESC
                    LIMIT 5000
                SQL,
            ],
            [
                'code' => 'audit_activity_by_user', 'name' => 'Audit — Activity Summary by User',
                'description' => 'Action counts per user: logins, failed attempts, authorisations, last active.',
                'category' => 'audit', 'sort_order' => 4,
                'sql_query' => <<<SQL
                    SELECT user_name,
                        COUNT(*)                       AS total_actions,
                        SUM(event = 'login')           AS logins,
                        SUM(event = 'login_failed')    AS failed_logins,
                        SUM(event = 'authorised')      AS authorisations,
                        SUM(event = 'status_changed')  AS status_changes,
                        MAX(created_at)                AS last_action_at
                    FROM audit_logs
                    GROUP BY user_name
                    ORDER BY total_actions DESC
                    LIMIT 1000
                SQL,
            ],
        ];
    }

    // ── 2. Operational / compliance reports (the domain tables) ────
    private function complianceReports(): array
    {
        return [
            // ── Turnaround: receipt → authorisation, per submission ──
            [
                'code' => 'tat_per_submission',
                'name' => 'Turnaround — Days from Receipt to Authorisation',
                'description' => 'Each authorised submission with calendar days from physical receipt to Director authorisation. Flags possible breaches of the required-by date.',
                'category' => 'compliance', 'sort_order' => 10,
                'sql_query' => <<<SQL
                    SELECT
                        s.reference_number,
                        s.priority,
                        s.received_at,
                        r.authorised_at,
                        DATEDIFF(r.authorised_at, s.received_at)            AS days_to_authorise,
                        s.results_required_by,
                        CASE
                            WHEN s.results_required_by IS NOT NULL
                             AND DATE(r.authorised_at) > s.results_required_by
                            THEN 'LATE' ELSE 'on_time'
                        END                                                 AS deadline_status
                    FROM submissions s
                    JOIN results r ON r.submission_id = s.id
                    WHERE r.authorised_at IS NOT NULL
                    ORDER BY r.authorised_at DESC
                    LIMIT 2000
                SQL,
            ],

            // ── Turnaround averages by priority ──────────────────────
            [
                'code' => 'tat_by_priority',
                'name' => 'Turnaround — Average Days by Priority',
                'description' => 'Average and worst-case turnaround grouped by priority — confirms urgent/emergency samples are actually expedited.',
                'category' => 'compliance', 'sort_order' => 11,
                'sql_query' => <<<SQL
                    SELECT
                        s.priority,
                        COUNT(*)                                            AS authorised_count,
                        ROUND(AVG(DATEDIFF(r.authorised_at, s.received_at)), 1) AS avg_days,
                        MAX(DATEDIFF(r.authorised_at, s.received_at))       AS max_days
                    FROM submissions s
                    JOIN results r ON r.submission_id = s.id
                    WHERE r.authorised_at IS NOT NULL AND s.received_at IS NOT NULL
                    GROUP BY s.priority
                    ORDER BY FIELD(s.priority, 'emergency', 'urgent', 'routine')
                SQL,
            ],

            // ── Work in progress / bottlenecks ───────────────────────
            [
                'code' => 'wip_open_submissions',
                'name' => 'Operations — Open Submissions & Age',
                'description' => 'Submissions not yet completed or cancelled, with days since receipt — bottleneck detection.',
                'category' => 'compliance', 'sort_order' => 12,
                'sql_query' => <<<SQL
                    SELECT
                        s.reference_number,
                        s.status,
                        s.priority,
                        s.received_at,
                        DATEDIFF(NOW(), COALESCE(s.received_at, s.submitted_at)) AS days_open
                    FROM submissions s
                    WHERE s.status NOT IN ('completed', 'cancelled', 'rejected')
                      AND s.deleted_at IS NULL
                    ORDER BY days_open DESC
                    LIMIT 2000
                SQL,
            ],

            // ── Result authorisations (traceability) ─────────────────
            [
                'code' => 'result_authorisations',
                'name' => 'Traceability — Result Authorisations',
                'description' => 'Every authorised result: reference, outcome, who authorised it and when. Core ISO 17025 traceability.',
                'category' => 'compliance', 'sort_order' => 13,
                'sql_query' => <<<SQL
                    SELECT
                        s.reference_number,
                        r.overall_outcome,
                        TRIM(CONCAT(u.first_name, ' ', u.last_name)) AS authorised_by,
                        r.authorised_at
                    FROM results r
                    JOIN submissions s ON s.id = r.submission_id
                    LEFT JOIN users u   ON u.id = r.authorised_by
                    WHERE r.authorised_at IS NOT NULL
                    ORDER BY r.authorised_at DESC
                    LIMIT 2000
                SQL,
            ],

            // ── Flagged tests (review queue / integrity) ─────────────
            [
                'code' => 'flagged_tests',
                'name' => 'Quality — Flagged Tests',
                'description' => 'Tests flagged for Director review, with the assigned analyst and current status.',
                'category' => 'compliance', 'sort_order' => 14,
                'sql_query' => <<<SQL
                    SELECT
                        sub.reference_number,
                        st.test_label,
                        st.status,
                        st.result_value,
                        st.result_qualifier,
                        TRIM(CONCAT(u.first_name, ' ', u.last_name)) AS analyst,
                        st.completed_at
                    FROM sample_tests st
                    JOIN samples sm     ON sm.id  = st.sample_id
                    JOIN submissions sub ON sub.id = sm.submission_id
                    LEFT JOIN users u   ON u.id   = st.assigned_to
                    WHERE st.status = 'flagged'
                    ORDER BY st.completed_at DESC
                    LIMIT 2000
                SQL,
            ],

            // ── Sample rejection reasons (which criteria fail) ───────
            [
                'code' => 'rejection_reasons',
                'name' => 'Quality — Sample Rejection Criteria',
                'description' => 'How often each of the 7 assessment criteria fails, among rejected assessments. Highlights recurring intake problems.',
                'category' => 'compliance', 'sort_order' => 15,
                'sql_query' => <<<SQL
                    SELECT
                        COUNT(*)                              AS rejected_assessments,
                        SUM(temperature_ok = 0)               AS temperature_fails,
                        SUM(storage_ok = 0)                   AS storage_fails,
                        SUM(transport_ok = 0)                 AS transport_fails,
                        SUM(packaging_ok = 0)                 AS packaging_fails,
                        SUM(colour_ok = 0)                    AS colour_fails,
                        SUM(odour_ok = 0)                     AS odour_fails,
                        SUM(weight_ok = 0)                    AS weight_fails
                    FROM sample_assessments
                    WHERE outcome = 'rejected'
                SQL,
            ],

            // ── Rejected-but-proceeded (risk area) ───────────────────
            [
                'code' => 'consent_after_rejection',
                'name' => 'Risk — Rejected Samples Proceeded by Consent',
                'description' => 'Samples that failed assessment but were tested anyway after client consent — a documented-risk area auditors scrutinise.',
                'category' => 'compliance', 'sort_order' => 16,
                'sql_query' => <<<SQL
                    SELECT
                        sub.reference_number,
                        sm.sample_code,
                        sa.outcome,
                        sa.client_decision,
                        sa.client_decision_at,
                        TRIM(CONCAT(u.first_name, ' ', u.last_name)) AS recorded_by
                    FROM sample_assessments sa
                    JOIN samples sm      ON sm.id  = sa.sample_id
                    JOIN submissions sub ON sub.id = sm.submission_id
                    LEFT JOIN users u    ON u.id   = sa.client_decision_recorded_by
                    WHERE sa.client_decision = 'consent_to_proceed'
                    ORDER BY sa.client_decision_at DESC
                    LIMIT 2000
                SQL,
            ],

            // ── Analyst workload / competence evidence ───────────────
            [
                'code' => 'analyst_workload',
                'name' => 'Competence — Tests by Analyst',
                'description' => 'Test counts per analyst (assigned, completed, flagged) — workload balance and competence evidence.',
                'category' => 'compliance', 'sort_order' => 17,
                'sql_query' => <<<SQL
                    SELECT
                        TRIM(CONCAT(u.first_name, ' ', u.last_name)) AS analyst,
                        COUNT(*)                          AS tests_assigned,
                        SUM(st.status = 'completed')      AS completed,
                        SUM(st.status = 'flagged')        AS flagged,
                        SUM(st.status IN ('queued','in_progress')) AS outstanding
                    FROM sample_tests st
                    JOIN users u ON u.id = st.assigned_to
                    WHERE st.assigned_to IS NOT NULL
                    GROUP BY st.assigned_to, analyst
                    ORDER BY tests_assigned DESC
                    LIMIT 1000
                SQL,
            ],

            // ── Records-control integrity check ──────────────────────
            [
                'code' => 'integrity_completed_without_authorisation',
                'name' => 'Integrity — Completed Without Authorised Result',
                'description' => 'Data-integrity check: submissions marked completed that have no authorised result. Should normally be empty.',
                'category' => 'compliance', 'sort_order' => 18,
                'sql_query' => <<<SQL
                    SELECT
                        s.reference_number,
                        s.status,
                        s.updated_at
                    FROM submissions s
                    LEFT JOIN results r
                        ON r.submission_id = s.id AND r.authorised_at IS NOT NULL
                    WHERE s.status = 'completed'
                      AND r.id IS NULL
                    ORDER BY s.updated_at DESC
                    LIMIT 1000
                SQL,
            ],

            // ── Complaints & resolution time ─────────────────────────
            [
                'code' => 'complaints_resolution',
                'name' => 'Quality — Complaints & Resolution Time',
                'description' => 'All complaints with status and days to resolution — ISO 17025 complaint-handling evidence.',
                'category' => 'compliance', 'sort_order' => 19,
                'sql_query' => <<<SQL
                    SELECT
                        subject,
                        status,
                        complainant_name,
                        created_at        AS lodged_at,
                        resolved_at,
                        CASE WHEN resolved_at IS NOT NULL
                             THEN DATEDIFF(resolved_at, created_at) END AS days_to_resolve
                    FROM complaints
                    ORDER BY created_at DESC
                    LIMIT 2000
                SQL,
            ],

            // ── Outstanding invoices ─────────────────────────────────
            [
                'code' => 'invoices_outstanding',
                'name' => 'Finance — Outstanding & Overdue Invoices',
                'description' => 'Unpaid or overdue invoices with amount and days overdue.',
                'category' => 'compliance', 'sort_order' => 20,
                'sql_query' => <<<SQL
                    SELECT
                        i.invoice_number,
                        s.reference_number,
                        i.total_amount_aud,
                        i.payment_status,
                        i.invoice_date,
                        i.payment_due_date,
                        CASE WHEN i.payment_due_date < CURDATE()
                              AND i.payment_status <> 'paid'
                             THEN DATEDIFF(CURDATE(), i.payment_due_date) END AS days_overdue
                    FROM invoices i
                    JOIN submissions s ON s.id = i.submission_id
                    WHERE i.payment_status IN ('unpaid', 'overdue')
                    ORDER BY i.payment_due_date ASC
                    LIMIT 2000
                SQL,
            ],

            // ── audit_failed_logins (now with country) ──────────────────────────────────
[
    'code' => 'audit_failed_logins', 'name' => 'Security — Failed Login Attempts',
    'description' => 'All failed login attempts with attempted email, source IP and country.',
    'category' => 'security', 'sort_order' => 2,
    'sql_query' => <<<SQL
        SELECT created_at AS attempted_at, user_name AS attempted_email,
               ip_address, country_code, country_name
        FROM audit_logs
        WHERE event = 'login_failed'
        ORDER BY created_at DESC
        LIMIT 5000
    SQL,
],
 
// ── audit_login_activity (now with country) ─────────────────────────────────
[
    'code' => 'audit_login_activity', 'name' => 'Security — Login Activity',
    'description' => 'Successful logins and logouts by user, with IP and country.',
    'category' => 'security', 'sort_order' => 3,
    'sql_query' => <<<SQL
        SELECT created_at AS occurred_at, event, user_name,
               ip_address, country_code, country_name
        FROM audit_logs
        WHERE event IN ('login', 'logout')
        ORDER BY created_at DESC
        LIMIT 5000
    SQL,
],
 
// ── NEW: logins grouped by country (this one IS chartable) ──────────────────
[
    'code' => 'audit_logins_by_country', 'name' => 'Security — Logins by Country',
    'description' => 'Count of successful logins grouped by country — quickly spots logins from unexpected locations.',
    'category' => 'security', 'sort_order' => 5,
    'sql_query' => <<<SQL
        SELECT
            COALESCE(country_name, 'Local / Unknown') AS country,
            COUNT(*)                                  AS login_count
        FROM audit_logs
        WHERE event = 'login'
        GROUP BY country_name
        ORDER BY login_count DESC
        LIMIT 500
    SQL,
],
        ];
    }
}