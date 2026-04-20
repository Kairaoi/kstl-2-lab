<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Models\Kstl\Complaint;
use App\Models\Kstl\Submission;
use App\Repositories\Kstl\ClientRepository;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    public function __construct(
        protected ClientRepository  $clientRepo,
        protected NotificationService $notifyService,
        protected AuditService       $auditService,
    ) {}

    // ── CLIENT: Lodge a complaint ──────────────────────────────────
    public function create()
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // Get client submissions for reference dropdown
        $submissions = $client
            ? Submission::where('client_id', $client->id)
                ->whereNotIn('status', ['submitted', 'cancelled'])
                ->orderByDesc('submitted_at')
                ->get(['id', 'reference_number', 'sample_name'])
            : collect();

        return view('kstl.client.complaints.create',
            compact('client', 'user', 'submissions'));
    }

    public function store(Request $request)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $validated = $request->validate([
            'subject'              => ['required', 'string', 'max:255'],
            'incident_date'        => ['required', 'date', 'before_or_equal:today'],
            'complaint_types'      => ['required', 'array', 'min:1'],
            'complaint_types.*'    => ['string', 'in:sample_handling,staff_conduct,delay_in_results,poor_customer_service,billing,other'],
            'other_complaint_type' => ['nullable', 'string', 'max:255'],
            'description'          => ['required', 'string', 'min:20', 'max:5000'],
            'submission_id'        => ['nullable', 'string', 'exists:submissions,id'],
        ]);

        $complaint = Complaint::create([
            'complainant_user_id'      => $user->id,
            'complainant_name'         => trim($user->first_name . ' ' . $user->last_name),
            'complainant_email'        => $user->email,
            'complainant_organisation' => $client?->company_name,
            'subject'                  => $validated['subject'],
            'incident_date'            => $validated['incident_date'],
            'complaint_types'          => $validated['complaint_types'],
            'other_complaint_type'     => $validated['other_complaint_type'] ?? null,
            'description'              => $validated['description'],
            'submission_id'            => $validated['submission_id'] ?? null,
            'status'                   => Complaint::STATUS_OPEN,
        ]);

        Log::info('Client lodged complaint', [
            'complaint_id' => $complaint->id,
            'user_id'      => $user->id,
            'subject'      => $complaint->subject,
        ]);

        // Audit log
        $this->auditService->logComplaintResponded($complaint, $complaint->getOriginal('status') ?? 'open');

        // Notify Director
        $complaint->load('submission');
        $this->notifyService->notifyComplaintReceived($complaint);

        return redirect()->route('client.complaints.show', $complaint->id)
            ->with('success', 'Your complaint has been submitted and the Laboratory Director has been notified. We will respond within 5 working days.');
    }

    public function index()
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $complaints = Complaint::where('complainant_user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('kstl.client.complaints.index',
            compact('client', 'user', 'complaints'));
    }

    public function show(string $id)
    {
        $user      = Auth::user();
        $complaint = Complaint::findOrFail($id);

        // Security — only the complainant or staff
        if ($complaint->complainant_user_id !== $user->id &&
            ! $user->hasAnyRole(['admin', 'super_admin', 'director', 'reception'])) {
            abort(403);
        }

        return view('kstl.client.complaints.show', compact('complaint', 'user'));
    }

    // ── STAFF: List all complaints ─────────────────────────────────
    public function staffIndex()
    {
        $complaints = Complaint::with(['complainant', 'assignedTo', 'submission'])
            ->orderByRaw("FIELD(status, 'open','under_investigation','resolved','closed')")
            ->orderByDesc('created_at')
            ->get();

        $counts = [
            'open'                => $complaints->where('status', 'open')->count(),
            'under_investigation' => $complaints->where('status', 'under_investigation')->count(),
            'resolved'            => $complaints->where('status', 'resolved')->count(),
            'closed'              => $complaints->where('status', 'closed')->count(),
        ];

        return view('kstl.director.complaints.index',
            compact('complaints', 'counts'));
    }

    // ── STAFF: Show + respond to complaint ────────────────────────
    public function staffShow(string $id)
    {
        $complaint = Complaint::with(['complainant', 'assignedTo', 'resolvedBy', 'submission'])
            ->findOrFail($id);

        return view('kstl.director.complaints.show', compact('complaint'));
    }

    // ── STAFF: Respond / update ────────────────────────────────────
    public function respond(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'lab_response'  => ['required', 'string'],
            'action_taken'  => ['nullable', 'string'],
            'status'        => ['required', 'in:open,under_investigation,resolved,closed'],
        ]);

        $updateData = [
            'lab_response' => $validated['lab_response'],
            'action_taken' => $validated['action_taken'] ?? null,
            'status'       => $validated['status'],
            'assigned_to'  => Auth::id(),
        ];

        if (in_array($validated['status'], ['resolved', 'closed']) && ! $complaint->resolved_at) {
            $updateData['resolved_at'] = now();
            $updateData['resolved_by'] = Auth::id();
        }

        $complaint->update($updateData);

        $this->auditService->logComplaintResponded($complaint, $complaint->getOriginal('status') ?? 'open');

        Log::info('Staff responded to complaint', [
            'complaint_id' => $complaint->id,
            'status'       => $validated['status'],
            'responded_by' => Auth::id(),
        ]);

        return redirect()->route('director.complaints.show', $complaint->id)
            ->with('success', 'Response recorded. Complaint status updated to: ' . ucfirst(str_replace('_', ' ', $validated['status'])));
    }
}