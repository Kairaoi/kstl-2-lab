<?php
// app/Models/AuditReportExecution.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Execution log row for the reporting subsystem.
 *
 * Aligned to the report_queries / report_executions schema:
 *   - table: report_executions
 *   - FK:    report_query_id  -> report_queries.id
 *   - parent relation: reportQuery()
 *
 * UUID keys to match the rest of the app.
 */
class AuditReportExecution extends Model
{
    use HasUuids;

    protected $table = 'report_executions';

    protected $fillable = [
        'report_query_id',
        'user_id',
        'parameters',
        'result_count',
        'execution_time_ms',
        'executed_at',
    ];

    protected $casts = [
        'parameters'        => 'array',
        'executed_at'       => 'datetime',
        'execution_time_ms' => 'decimal:2',
    ];

    public function reportQuery(): BelongsTo
    {
        return $this->belongsTo(ReportQuery::class, 'report_query_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}