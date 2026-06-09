<?php

namespace Database\Seeders;

use App\Models\ReportQuery;
use Illuminate\Database\Seeder;

/**
 * Analytics & Statistics Reports Seeder
 *
 * Operational dashboarding reports for lab management:
 *   - Submission trends (volume, status, priority)
 *   - Test demand and pass/fail rates
 *   - Turnaround performance
 *   - Revenue and invoice collection
 *   - Client activity
 *   - Analyst workload
 *
 * These complement the audit/compliance reports (AuditReportsSeeder) —
 * those answer "what happened?", these answer "how are we performing?".
 */
class AnalyticsReportsSeeder extends Seeder
{
    private const ANALYTICS_ROLES = ['director'];
    private const FINANCE_ROLES   = ['director'];

    public function run(): void
    {
        $reports = $this->analyticsReports();

        foreach ($reports as $r) {
            $roles = $r['finance_only'] ?? false
                ? self::FINANCE_ROLES
                : self::ANALYTICS_ROLES;

            ReportQuery::updateOrCreate(
                ['code' => $r['code']],
                [
                    'name'          => $r['name'],
                    'description'   => $r['description'],
                    'category'      => 'analytics',
                    'sql_query'     => $r['sql_query'],
                    'allowed_roles' => $roles,
                    'parameters'    => $r['parameters'] ?? null,
                    'is_active'     => true,
                    'sort_order'    => $r['sort_order'],
                ]
            );
        }
    }

    private function analyticsReports(): array
    {
        return [

            // ── Submission Volume ────────────────────────────────────────────
            [
                'code'       => 'analytics_submissions_by_month',
                'name'       => 'Submissions — Monthly Volume (12 months)',
                'description'=> 'Submission counts per calendar month for the past 12 months, broken down by completion vs. rejection/cancellation and showing urgent/emergency counts.',
                'sort_order' => 100,
                'sql_query'  => <<<SQL
                    SELECT
                        DATE_FORMAT(submitted_at, '%Y-%m')            AS month,
                        COUNT(*)                                       AS total_submitted,
                        SUM(status = 'completed')                     AS completed,
                        SUM(status IN ('rejected','cancelled'))        AS rejected_cancelled,
                        SUM(priority = 'urgent')                      AS urgent,
                        SUM(priority = 'emergency')                   AS emergency
                    FROM submissions
                    WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                      AND deleted_at IS NULL
                    GROUP BY DATE_FORMAT(submitted_at, '%Y-%m')
                    ORDER BY month DESC
                    LIMIT 24
                SQL,
            ],

            // ── Submission Status Distribution ───────────────────────────────
            [
                'code'       => 'analytics_submissions_by_status',
                'name'       => 'Submissions — Current Status Distribution',
                'description'=> 'Count of submissions in each status, showing how many are at each stage of the lab workflow right now.',
                'sort_order' => 101,
                'sql_query'  => <<<SQL
                    SELECT
                        status,
                        COUNT(*)                                         AS count,
                        ROUND(100 * COUNT(*) / SUM(COUNT(*)) OVER (), 1) AS pct_of_total
                    FROM submissions
                    WHERE deleted_at IS NULL
                    GROUP BY status
                    ORDER BY FIELD(status,
                        'submitted','received','assessing','accepted',
                        'consent_to_proceed','testing','awaiting_authorisation',
                        'authorised','completed','rejected','cancelled')
                SQL,
            ],

            // ── Priority Distribution with TAT ───────────────────────────────
            [
                'code'       => 'analytics_priority_tat',
                'name'       => 'Submissions — Priority Breakdown & Average TAT',
                'description'=> 'Submission counts and average turnaround days (receipt → Director authorisation) grouped by priority — confirms urgent and emergency samples are expedited above routine.',
                'sort_order' => 102,
                'sql_query'  => <<<SQL
                    SELECT
                        s.priority,
                        COUNT(s.id)                                              AS total_submissions,
                        SUM(s.status = 'completed')                             AS completed,
                        ROUND(AVG(DATEDIFF(r.authorised_at, s.received_at)), 1) AS avg_days_tat,
                        MAX(DATEDIFF(r.authorised_at, s.received_at))            AS max_days_tat,
                        MIN(DATEDIFF(r.authorised_at, s.received_at))            AS min_days_tat
                    FROM submissions s
                    LEFT JOIN results r
                        ON r.submission_id = s.id AND r.authorised_at IS NOT NULL
                    WHERE s.deleted_at IS NULL
                    GROUP BY s.priority
                    ORDER BY FIELD(s.priority, 'emergency', 'urgent', 'routine')
                SQL,
            ],

            // ── Sample Type Breakdown ────────────────────────────────────────
            [
                'code'       => 'analytics_sample_type_breakdown',
                'name'       => 'Submissions — Sample Type Breakdown',
                'description'=> 'Distribution of submissions across sample types (fish, shellfish, water, sediment, etc.) — useful for understanding lab workload composition.',
                'sort_order' => 103,
                'sql_query'  => <<<SQL
                    SELECT
                        sample_type,
                        COUNT(*)                                         AS submission_count,
                        ROUND(100 * COUNT(*) / SUM(COUNT(*)) OVER (), 1) AS pct_of_total
                    FROM submissions
                    WHERE deleted_at IS NULL
                    GROUP BY sample_type
                    ORDER BY submission_count DESC
                SQL,
            ],

            // ── Test Demand Ranking ──────────────────────────────────────────
            [
                'code'       => 'analytics_test_demand',
                'name'       => 'Tests — Most Requested (Demand Ranking)',
                'description'=> 'Each test type ranked by how many times it has been requested, with completion rate and average hours from start to completion.',
                'sort_order' => 110,
                'sql_query'  => <<<SQL
                    SELECT
                        test_key,
                        MAX(test_label)                                AS test_label,
                        MAX(test_category)                             AS category,
                        COUNT(*)                                       AS times_requested,
                        SUM(status = 'completed')                     AS completed,
                        SUM(status IN ('queued','in_progress'))        AS outstanding,
                        ROUND(AVG(
                            CASE WHEN started_at IS NOT NULL AND completed_at IS NOT NULL
                                 THEN TIMESTAMPDIFF(HOUR, started_at, completed_at)
                            END
                        ), 1)                                          AS avg_hours_to_complete
                    FROM sample_tests
                    GROUP BY test_key
                    ORDER BY times_requested DESC
                    LIMIT 50
                SQL,
            ],

            // ── Test Pass / Fail Rates ───────────────────────────────────────
            [
                'code'       => 'analytics_test_pass_fail_rates',
                'name'       => 'Tests — Pass / Fail Rates by Test Type',
                'description'=> 'For each completed test type, the count of pass-equivalent vs. fail-equivalent results and the overall pass rate — identifies which parameters most frequently fail.',
                'sort_order' => 111,
                'sql_query'  => <<<SQL
                    SELECT
                        test_key,
                        MAX(test_label)                                          AS test_label,
                        COUNT(*)                                                 AS total_completed,
                        SUM(result_qualifier IN ('pass','not_detected','less_than'))  AS passed,
                        SUM(result_qualifier IN ('fail','detected','greater_than'))   AS failed,
                        ROUND(
                            100 * SUM(result_qualifier IN ('pass','not_detected','less_than'))
                                / NULLIF(COUNT(*), 0),
                            1
                        )                                                        AS pass_rate_pct
                    FROM sample_tests
                    WHERE status = 'completed'
                      AND result_qualifier NOT IN ('pending','equal_to')
                    GROUP BY test_key
                    ORDER BY total_completed DESC
                    LIMIT 50
                SQL,
            ],

            // ── Result Outcomes ──────────────────────────────────────────────
            [
                'code'       => 'analytics_result_outcomes',
                'name'       => 'Results — Overall Outcome Distribution',
                'description'=> 'Count and percentage of authorised results by overall outcome (pass, fail, inconclusive) — headline quality indicator.',
                'sort_order' => 112,
                'sql_query'  => <<<SQL
                    SELECT
                        COALESCE(overall_outcome, 'not_set')             AS overall_outcome,
                        COUNT(*)                                         AS count,
                        ROUND(100 * COUNT(*) / SUM(COUNT(*)) OVER (), 1) AS pct_of_total
                    FROM results
                    WHERE authorised_at IS NOT NULL
                    GROUP BY overall_outcome
                    ORDER BY count DESC
                SQL,
            ],

            // ── Client Activity ──────────────────────────────────────────────
            [
                'code'       => 'analytics_client_activity',
                'name'       => 'Clients — Submission Activity (30 / 60 / 90 Days)',
                'description'=> 'For each client, how many submissions they have placed in the last 30, 60, and 90 days vs. all time — identifies active, lapsed, and churned clients.',
                'sort_order' => 120,
                'sql_query'  => <<<SQL
                    SELECT
                        c.company_name,
                        COUNT(s.id)                                                                           AS all_time,
                        SUM(s.submitted_at >= DATE_SUB(NOW(), INTERVAL 30 DAY))                               AS last_30_days,
                        SUM(s.submitted_at >= DATE_SUB(NOW(), INTERVAL 60 DAY))                               AS last_60_days,
                        SUM(s.submitted_at >= DATE_SUB(NOW(), INTERVAL 90 DAY))                               AS last_90_days,
                        MAX(s.submitted_at)                                                                    AS last_submission_at,
                        SUM(s.status = 'completed')                                                           AS completed
                    FROM clients c
                    LEFT JOIN submissions s ON s.client_id = c.id AND s.deleted_at IS NULL
                    WHERE c.deleted_at IS NULL
                    GROUP BY c.id, c.company_name
                    ORDER BY last_30_days DESC, all_time DESC
                    LIMIT 100
                SQL,
            ],

            // ── Top Clients by Volume ────────────────────────────────────────
            [
                'code'       => 'analytics_top_clients_by_volume',
                'name'       => 'Clients — Top Clients by Submission Volume',
                'description'=> 'All-time submission count per client with completion rate and date of most recent submission.',
                'sort_order' => 121,
                'sql_query'  => <<<SQL
                    SELECT
                        c.company_name,
                        COUNT(s.id)                                               AS total_submissions,
                        SUM(s.status = 'completed')                              AS completed,
                        ROUND(100 * SUM(s.status = 'completed') / NULLIF(COUNT(s.id), 0), 1) AS completion_rate_pct,
                        MAX(s.submitted_at)                                       AS last_submission_at
                    FROM clients c
                    LEFT JOIN submissions s ON s.client_id = c.id AND s.deleted_at IS NULL
                    WHERE c.deleted_at IS NULL
                    GROUP BY c.id, c.company_name
                    ORDER BY total_submissions DESC
                    LIMIT 50
                SQL,
            ],

            // ── Analyst Productivity ─────────────────────────────────────────
            [
                'code'       => 'analytics_analyst_productivity_monthly',
                'name'       => 'Analysts — Monthly Productivity',
                'description'=> 'Tests completed per analyst per calendar month for the past 6 months — shows workload distribution and trends over time.',
                'sort_order' => 130,
                'sql_query'  => <<<SQL
                    SELECT
                        DATE_FORMAT(st.completed_at, '%Y-%m')          AS month,
                        TRIM(CONCAT(u.first_name, ' ', u.last_name))   AS analyst,
                        COUNT(*)                                        AS tests_completed,
                        SUM(st.status = 'flagged')                     AS flagged
                    FROM sample_tests st
                    JOIN users u ON u.id = st.assigned_to
                    WHERE st.status = 'completed'
                      AND st.completed_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                    GROUP BY DATE_FORMAT(st.completed_at, '%Y-%m'), st.assigned_to, analyst
                    ORDER BY month DESC, tests_completed DESC
                    LIMIT 200
                SQL,
            ],

            // ── Revenue by Month ─────────────────────────────────────────────
            [
                'code'        => 'analytics_revenue_by_month',
                'name'        => 'Finance — Monthly Revenue & Collection',
                'description' => 'Total amount invoiced and total amount collected (paid) per calendar month — tracks billing pipeline health.',
                'sort_order'  => 140,
                'finance_only'=> true,
                'sql_query'   => <<<SQL
                    SELECT
                        DATE_FORMAT(invoice_date, '%Y-%m')                                           AS month,
                        COUNT(*)                                                                     AS invoices_issued,
                        ROUND(SUM(total_amount_aud), 2)                                             AS total_invoiced_aud,
                        ROUND(SUM(CASE WHEN payment_status = 'paid' THEN total_amount_aud ELSE 0 END), 2) AS collected_aud,
                        ROUND(SUM(CASE WHEN payment_status IN ('unpaid','overdue') THEN total_amount_aud ELSE 0 END), 2) AS outstanding_aud
                    FROM invoices
                    GROUP BY DATE_FORMAT(invoice_date, '%Y-%m')
                    ORDER BY month DESC
                    LIMIT 24
                SQL,
            ],

            // ── Invoice Collection Rate ──────────────────────────────────────
            [
                'code'        => 'analytics_invoice_collection_rate',
                'name'        => 'Finance — Invoice Collection Rate by Status',
                'description' => 'Breakdown of all invoices by payment status — shows the proportion paid, outstanding, overdue, or waived.',
                'sort_order'  => 141,
                'finance_only'=> true,
                'sql_query'   => <<<SQL
                    SELECT
                        payment_status,
                        COUNT(*)                                         AS invoice_count,
                        ROUND(SUM(total_amount_aud), 2)                 AS total_aud,
                        ROUND(100 * COUNT(*) / SUM(COUNT(*)) OVER (), 1) AS pct_of_invoices
                    FROM invoices
                    GROUP BY payment_status
                    ORDER BY invoice_count DESC
                SQL,
            ],

            // ── Top Clients by Revenue ───────────────────────────────────────
            [
                'code'        => 'analytics_top_clients_by_revenue',
                'name'        => 'Finance — Top Clients by Revenue',
                'description' => 'Total invoiced amount per client with collection status breakdown — identifies highest-value clients and any large outstanding balances.',
                'sort_order'  => 142,
                'finance_only'=> true,
                'sql_query'   => <<<SQL
                    SELECT
                        i.bill_to_company,
                        COUNT(i.id)                                                                       AS invoices,
                        ROUND(SUM(i.total_amount_aud), 2)                                                AS total_invoiced_aud,
                        ROUND(SUM(CASE WHEN i.payment_status = 'paid' THEN i.total_amount_aud ELSE 0 END), 2)      AS paid_aud,
                        ROUND(SUM(CASE WHEN i.payment_status IN ('unpaid','overdue') THEN i.total_amount_aud ELSE 0 END), 2) AS outstanding_aud
                    FROM invoices i
                    GROUP BY i.bill_to_company
                    ORDER BY total_invoiced_aud DESC
                    LIMIT 50
                SQL,
            ],

            // ── Overdue Submissions ──────────────────────────────────────────
            [
                'code'       => 'analytics_overdue_submissions',
                'name'       => 'Operations — Submissions Past Required-By Date',
                'description'=> 'Open submissions whose results_required_by date has passed — these are at risk of breaching client commitments and should be escalated.',
                'sort_order' => 150,
                'sql_query'  => <<<SQL
                    SELECT
                        s.reference_number,
                        s.status,
                        s.priority,
                        c.company_name                                      AS client,
                        s.results_required_by,
                        DATEDIFF(NOW(), s.results_required_by)              AS days_overdue,
                        s.sample_name
                    FROM submissions s
                    JOIN clients c ON c.id = s.client_id
                    WHERE s.results_required_by < CURDATE()
                      AND s.status NOT IN ('completed','authorised','cancelled','rejected')
                      AND s.deleted_at IS NULL
                    ORDER BY days_overdue DESC
                    LIMIT 500
                SQL,
            ],

            // ── Open Urgent / Emergency ──────────────────────────────────────
            [
                'code'       => 'analytics_open_urgent_emergency',
                'name'       => 'Operations — Open Urgent & Emergency Submissions',
                'description'=> 'All currently open submissions with priority = urgent or emergency, ordered by age — ensures high-priority work is not lost in the queue.',
                'sort_order' => 151,
                'sql_query'  => <<<SQL
                    SELECT
                        s.reference_number,
                        s.priority,
                        s.status,
                        c.company_name                                           AS client,
                        s.sample_name,
                        s.received_at,
                        DATEDIFF(NOW(), COALESCE(s.received_at, s.submitted_at)) AS days_open,
                        s.results_required_by
                    FROM submissions s
                    JOIN clients c ON c.id = s.client_id
                    WHERE s.priority IN ('urgent','emergency')
                      AND s.status NOT IN ('completed','authorised','cancelled','rejected')
                      AND s.deleted_at IS NULL
                    ORDER BY FIELD(s.priority,'emergency','urgent'), days_open DESC
                    LIMIT 200
                SQL,
            ],

            // ── Full Results Listing (flat denormalised export) ───────────────
            [
                'code'       => 'analytics_full_results_listing',
                'name'       => 'Results — Full Test Results Listing',
                'description'=> 'Flat export of all completed test results joined with submission and sample details: company, sampling date, reference, transport, collection location, sample ID, species, quantity, test name, and formatted result value.',
                'sort_order' => 160,
                'sql_query'  => <<<SQL
                    SELECT
                        c.company_name                                                                    AS `Company Name`,
                        DATE_FORMAT(s.collected_at, '%d-%b-%y')                                          AS `Sampling Date`,
                        s.reference_number                                                                AS `Reference ID`,
                        CONCAT(UPPER(LEFT(s.transport_method, 1)), LOWER(SUBSTRING(s.transport_method, 2))) AS `Transport`,
                        COALESCE(
                            REPLACE(REPLACE(s.transport_detail, '_', ' '), '-', ' '),
                            CONCAT(UPPER(LEFT(s.transport_method, 1)), LOWER(SUBSTRING(s.transport_method, 2)))
                        )                                                                                 AS `Transport Methods`,
                        s.collection_location                                                             AS `Collection Location`,
                        sm.sample_code                                                                    AS `Sample ID`,
                        sm.common_name                                                                    AS `Sample`,
                        sm.scientific_name                                                                AS `Scientific Name`,
                        CONCAT(TRIM(TRAILING '.' FROM TRIM(TRAILING '0' FROM CAST(sm.quantity AS CHAR))), sm.quantity_unit) AS `Quantity/Weight`,
                        COALESCE(st.test_label, REPLACE(st.test_key, '_', ' '))                          AS `Test`,
                        CASE
                            WHEN st.result_qualifier = 'less_than'
                                THEN CONCAT('<', st.result_value,
                                     CASE WHEN st.result_unit = '%'      THEN '%'
                                          WHEN st.result_unit IS NOT NULL THEN CONCAT(' ', st.result_unit)
                                          ELSE '' END)
                            WHEN st.result_qualifier = 'greater_than'
                                THEN CONCAT('>', st.result_value,
                                     CASE WHEN st.result_unit = '%'      THEN '%'
                                          WHEN st.result_unit IS NOT NULL THEN CONCAT(' ', st.result_unit)
                                          ELSE '' END)
                            WHEN st.result_qualifier = 'not_detected'
                                THEN 'Not Detected'
                            WHEN st.result_qualifier = 'detected'
                                THEN CASE
                                    WHEN st.result_value IS NOT NULL AND st.result_unit = '%' THEN CONCAT(st.result_value, '%')
                                    WHEN st.result_value IS NOT NULL AND st.result_unit IS NOT NULL THEN CONCAT(st.result_value, ' ', st.result_unit)
                                    WHEN st.result_value IS NOT NULL THEN st.result_value
                                    ELSE 'Detected'
                                END
                            WHEN st.result_qualifier IN ('pass', 'fail')
                                THEN CONCAT(UPPER(LEFT(st.result_qualifier, 1)), LOWER(SUBSTRING(st.result_qualifier, 2)))
                            WHEN st.result_value IS NOT NULL AND st.result_unit = '%'
                                THEN CONCAT(st.result_value, '%')
                            WHEN st.result_value IS NOT NULL AND st.result_unit IS NOT NULL
                                THEN CONCAT(st.result_value, ' ', st.result_unit)
                            WHEN st.result_value IS NOT NULL
                                THEN st.result_value
                            ELSE '—'
                        END                                                                               AS `Result`
                    FROM submissions s
                    JOIN clients c   ON c.id  = s.client_id
                    JOIN samples sm  ON sm.submission_id = s.id
                    JOIN sample_tests st ON st.sample_id = sm.id
                    WHERE st.status = 'completed'
                      AND s.deleted_at IS NULL
                      AND (NULLIF(:start_date,         '') IS NULL OR s.collected_at      >= :start_date)
                      AND (NULLIF(:end_date,           '') IS NULL OR s.collected_at      <= :end_date)
                      AND (NULLIF(:company,            '') IS NULL OR c.company_name       LIKE CONCAT('%', :company, '%'))
                      AND (NULLIF(:reference_id,       '') IS NULL OR s.reference_number   LIKE CONCAT('%', :reference_id, '%'))
                      AND (NULLIF(:transport,          '') IS NULL OR s.transport_method   LIKE CONCAT('%', :transport, '%'))
                      AND (NULLIF(:transport_methods,  '') IS NULL OR COALESCE(s.transport_detail, s.transport_method) LIKE CONCAT('%', :transport_methods, '%'))
                      AND (NULLIF(:collection_location,'') IS NULL OR s.collection_location LIKE CONCAT('%', :collection_location, '%'))
                      AND (NULLIF(:sample_id,          '') IS NULL OR sm.sample_code        LIKE CONCAT('%', :sample_id, '%'))
                      AND (NULLIF(:sample,             '') IS NULL OR sm.common_name        LIKE CONCAT('%', :sample, '%'))
                      AND (NULLIF(:scientific_name,    '') IS NULL OR sm.scientific_name    LIKE CONCAT('%', :scientific_name, '%'))
                      AND (NULLIF(:qty_min,            '') IS NULL OR sm.quantity           >= :qty_min)
                      AND (NULLIF(:qty_max,            '') IS NULL OR sm.quantity           <= :qty_max)
                      AND (NULLIF(:test,               '') IS NULL OR st.test_label         LIKE CONCAT('%', :test, '%')
                                                                   OR REPLACE(st.test_key, '_', ' ') LIKE CONCAT('%', :test, '%'))
                    ORDER BY s.collected_at DESC, s.reference_number, sm.sample_code, st.test_key
                    LIMIT 2000
                SQL,
                'parameters' => [
                    ['name' => 'start_date',         'label' => 'From Date',          'type' => 'date',   'placeholder' => ''],
                    ['name' => 'end_date',            'label' => 'To Date',            'type' => 'date',   'placeholder' => ''],
                    ['name' => 'company',             'label' => 'Company Name',       'type' => 'text',   'placeholder' => 'e.g. Pacific Fisheries'],
                    ['name' => 'reference_id',        'label' => 'Reference ID',       'type' => 'text',   'placeholder' => 'e.g. KSTL-2026-00005'],
                    ['name' => 'transport',           'label' => 'Transport',          'type' => 'text',   'placeholder' => 'frozen / chilled / fresh'],
                    ['name' => 'transport_methods',   'label' => 'Transport Methods',  'type' => 'text',   'placeholder' => 'e.g. Air freight'],
                    ['name' => 'collection_location', 'label' => 'Collection Location','type' => 'text',   'placeholder' => 'e.g. Abemama'],
                    ['name' => 'sample_id',           'label' => 'Sample ID',          'type' => 'text',   'placeholder' => 'e.g. KSTL-S-00036'],
                    ['name' => 'sample',              'label' => 'Sample',             'type' => 'text',   'placeholder' => 'e.g. Skipjack Tuna'],
                    ['name' => 'scientific_name',     'label' => 'Scientific Name',    'type' => 'text',   'placeholder' => 'e.g. Katsuwonus'],
                    ['name' => 'qty_min',             'label' => 'Min Weight (kg)',    'type' => 'number', 'placeholder' => ''],
                    ['name' => 'qty_max',             'label' => 'Max Weight (kg)',    'type' => 'number', 'placeholder' => ''],
                    ['name' => 'test',                'label' => 'Test',               'type' => 'text',   'placeholder' => 'e.g. Moisture'],
                ],
            ],

        ];
    }
}
