<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()->orderByDesc('created_at');

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('user')) {
            $term = '%' . $request->user . '%';
            $query->where(function ($q) use ($term) {
                $q->where('user_name', 'like', $term)
                  ->orWhere('user_id',   'like', $term);
            });
        }

        if ($request->filled('entity')) {
            $query->where('auditable_type', 'like', '%' . $request->entity . '%');
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->paginate(50);

        // Summary counts for the current filter
        $summary = [
            'total'    => AuditLog::count(),
            'today'    => AuditLog::whereDate('created_at', today())->count(),
            'this_week'=> AuditLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        Log::info('Auditor viewed audit logs', [
            'user_id' => Auth::id(),
            'filters' => $request->only(['event', 'user', 'entity', 'from', 'to']),
        ]);

        return view('kstl.auditor.audit.index', compact('logs', 'summary'));
    }

    public function show(string $id)
    {
        $log = AuditLog::findOrFail($id);

        return view('kstl.auditor.audit.show', compact('log'));
    }
}