<?php

namespace App\Http\Controllers\Kstl;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\Controller;
use App\Repositories\Kstl\ClientRepository;
use App\Repositories\Kstl\SubmissionRepository;
use App\Models\Kstl\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Services\AuditService;

class ClientController extends Controller
{
   public function __construct(
    protected ClientRepository     $clientRepo,
    protected SubmissionRepository $submissionRepo,
    protected AuditService         $auditService,
) {}
    // ── Dashboard ──────────────────────────────────────────────────────────────

    public function dashboard(Request $request)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $counts = $client
            ? $this->submissionRepo->countByClientId($client->id)
            : ['total' => 0, 'pending' => 0, 'in_progress' => 0, 'results_ready' => 0, 'cancelled' => 0];

        $summary = [
            'total_submissions'   => $counts['total'],
            'pending_submissions' => $counts['pending'],
            'results_ready'       => $counts['results_ready'],
            'unpaid_invoices'     => 0, // InvoiceRepository::countUnpaidByClientId($client->id)
            'open_complaints'     => 0, // ComplaintRepository::countOpenByClientId($client->id)
        ];

        Log::info('Client accessed dashboard', [
            'user_id'   => $user->id,
            'client_id' => $client?->id,
        ]);

        return view('kstl.client.dashboard', compact('user', 'client', 'summary'));
    }

    // ── Submissions ────────────────────────────────────────────────────────────

    public function submissionsIndex(Request $request)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $submissions = $client
            ? $this->submissionRepo->getByClientId(
                clientId: $client->id,
                status:   $request->query('status'),
                search:   $request->query('search'),
                perPage:  15,
            )
            : collect();

        Log::info('Client viewed submissions list', [
            'user_id'   => $user->id,
            'client_id' => $client?->id,
        ]);

        return view('kstl.client.submissions.index', compact('client', 'submissions'));
    }

    public function submissionsCreate()
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // Check service agreement before allowing submission
        if (! $this->clientRepo->hasSignedAgreement($client->id)) {
            return redirect()->route('client.dashboard')
                ->with('warning', 'You must sign the service agreement before submitting samples.');
        }

        return view('kstl.client.submissions.create', compact('client'));
    }

    public function submissionsStore(Request $request)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        if (! $this->clientRepo->hasSignedAgreement($client->id)) {
            return redirect()->route('client.dashboard')
                ->with('warning', 'You must sign the service agreement before submitting samples.');
        }

        try {
            $validated = $request->validate([
                // Sample info (Schedule 1: Sample table)
                'sample_name'           => ['required', 'string', 'max:255'],
                'scientific_name'       => ['nullable', 'string', 'max:255'],
                'sample_description'    => ['nullable', 'string', 'max:2000'],
                'sample_type'           => ['required', 'in:fish,shellfish,seaweed,water,sediment,other'],
                'sample_quantity'       => ['required', 'numeric', 'min:0'],
                'sample_quantity_unit'  => ['required', 'in:g,kg,ml,L'],
                'collected_at'          => ['required', 'date', 'before_or_equal:today'],
                'collection_location'   => ['nullable', 'string', 'max:255'],

                // Tests requested (Schedule 1: Chemical / Microbiology)
                'tests_requested'       => ['nullable', 'array'],
                'tests_requested.*'     => ['string', 'in:total_coliforms,e_coli,enterococci,yeast_mold,apc,e_coli_coliform,staph_aureus,histamine,moisture,ph,conductivity,water_activity'],
                'tests_other'           => ['nullable', 'string', 'max:1000'],

                // Transport method (Schedule 1: Frozen / Chill / Fresh)
                'transport_method'      => ['required', 'in:frozen,chilled,fresh'],

                // Special instructions
                'priority'              => ['nullable', 'in:routine,urgent,emergency'],
                'special_instructions'  => ['nullable', 'string', 'max:2000'],
                'results_required_by'   => ['nullable', 'date', 'after:today'],

                // Declaration
                'declaration_accepted'  => ['required', 'in:1'],

                // Submitter
                'submitter_name'        => ['required', 'string', 'max:255'],
                'submitter_designation' => ['nullable', 'string', 'max:255'],
            ]);

            $submission = DB::transaction(function () use ($validated, $client) {
                return $this->submissionRepo->create([
                    'client_id'             => $client->id,
                    'sample_name'           => $validated['sample_name'],
                    'scientific_name'       => $validated['scientific_name']       ?? null,
                    'sample_description'    => $validated['sample_description']    ?? null,
                    'sample_type'           => $validated['sample_type'],
                    'sample_quantity'       => $validated['sample_quantity'],
                    'sample_quantity_unit'  => $validated['sample_quantity_unit'],
                    'collected_at'          => $validated['collected_at'],
                    'collection_location'   => $validated['collection_location']   ?? null,
                    'tests_requested'       => $validated['tests_requested']       ?? [],
                    'tests_other'           => $validated['tests_other']           ?? null,
                    'transport_method'      => $validated['transport_method'],
                    'priority'              => $validated['priority']              ?? 'routine',
                    'special_instructions'  => $validated['special_instructions']  ?? null,
                    'results_required_by'   => $validated['results_required_by']   ?? null,
                    'submitter_name'        => $validated['submitter_name'],
                    'submitter_designation' => $validated['submitter_designation'] ?? null,
                    'application_date'      => now()->toDateString(),
                ]);
            });

            Log::info('Client created submission', [
                'user_id'          => $user->id,
                'client_id'        => $client->id,
                'submission_id'    => $submission->id,
                'reference_number' => $submission->reference_number,
                'sample_type'      => $submission->sample_type,
                'transport_method' => $submission->transport_method,
            ]);

            $this->auditService->logSubmissionCreated($submission);

            return redirect()
                ->route('client.submissions.show', $submission->id)
                ->with('success', "Submission {$submission->reference_number} created successfully.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;

        } catch (\Exception $e) {
            Log::error('Submission creation failed', [
                'user_id'   => $user->id,
                'client_id' => $client->id,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create submission. Please try again.')
                ->withInput();
        }
    }
    public function submissionsShow(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $submission = $this->submissionRepo->findByIdForClient($id, $client->id);

        if (! $submission) {
            abort(404, 'Submission not found.');
        }

        Log::info('Client viewed submission', [
            'user_id'       => $user->id,
            'client_id'     => $client->id,
            'submission_id' => $submission->id,
            'reference'     => $submission->reference_number,
        ]);

        return view('kstl.client.submissions.show', compact('client', 'submission'));
    }

    public function submissionsEdit(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $submission = $this->submissionRepo->findByIdForClient($id, $client->id);

        if (! $submission) {
            abort(404, 'Submission not found.');
        }

        if (! $submission->isEditable()) {
            return redirect()->route('client.submissions.show', $id)
                ->with('error', 'This submission can no longer be edited — it has already been received by the lab.');
        }

        Log::info('Client accessed submission edit form', [
            'user_id'       => $user->id,
            'client_id'     => $client->id,
            'submission_id' => $submission->id,
            'reference'     => $submission->reference_number,
        ]);

        return view('kstl.client.submissions.edit', compact('client', 'submission'));
    }

    public function submissionsUpdate(Request $request, string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $submission = $this->submissionRepo->findByIdForClient($id, $client->id);

        if (! $submission) {
            abort(404, 'Submission not found.');
        }

        if (! $submission->isEditable()) {
            return redirect()->route('client.submissions.show', $id)
                ->with('error', 'This submission can no longer be edited.');
        }

        try {
            $validated = $request->validate([
                'sample_name'           => ['required', 'string', 'max:255'],
                'scientific_name'       => ['nullable', 'string', 'max:255'],
                'sample_description'    => ['nullable', 'string', 'max:2000'],
                'sample_type'           => ['required', 'in:fish,shellfish,seaweed,water,sediment,other'],
                'sample_quantity'       => ['required', 'numeric', 'min:0'],
                'sample_quantity_unit'  => ['required', 'in:g,kg,ml,L'],
                'collected_at'          => ['required', 'date', 'before_or_equal:today'],
                'collection_location'   => ['nullable', 'string', 'max:255'],
                'tests_requested'       => ['nullable', 'array'],
                'tests_requested.*'     => ['string'],
                'tests_other'           => ['nullable', 'string', 'max:1000'],
                'transport_method'      => ['required', 'in:frozen,chilled,fresh'],
                'priority'              => ['nullable', 'in:routine,urgent,emergency'],
                'special_instructions'  => ['nullable', 'string', 'max:2000'],
                'results_required_by'   => ['nullable', 'date', 'after:today'],
                'submitter_name'        => ['required', 'string', 'max:255'],
                'submitter_designation' => ['nullable', 'string', 'max:255'],
            ]);

            DB::transaction(function () use ($validated, $submission) {
                $this->submissionRepo->updateById($submission->id, $validated);
            });

            Log::info('Client updated submission', [
                'user_id'       => $user->id,
                'client_id'     => $client->id,
                'submission_id' => $submission->id,
                'reference'     => $submission->reference_number,
            ]);

            return redirect()
                ->route('client.submissions.show', $submission->id)
                ->with('success', 'Submission updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;

        } catch (\Exception $e) {
            Log::error('Submission update failed', [
                'user_id'       => $user->id,
                'client_id'     => $client->id,
                'submission_id' => $id,
                'error'         => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update submission. Please try again.')
                ->withInput();
        }
    }

    public function submissionsDestroy(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $submission = $this->submissionRepo->findByIdForClient($id, $client->id);

        if (! $submission) {
            abort(404, 'Submission not found.');
        }

        if (! $submission->isCancellable()) {
            return redirect()->route('client.submissions.show', $id)
                ->with('error', 'This submission cannot be cancelled at this stage.');
        }

        try {
            $this->submissionRepo->cancel($submission->id, 'Cancelled by client.');

            Log::info('Client cancelled submission', [
                'user_id'       => $user->id,
                'client_id'     => $client->id,
                'submission_id' => $submission->id,
                'reference'     => $submission->reference_number,
            ]);

            return redirect()
                ->route('client.submissions.index')
                ->with('success', "Submission {$submission->reference_number} has been cancelled.");

        } catch (\Exception $e) {
            Log::error('Submission cancellation failed', [
                'user_id'       => $user->id,
                'client_id'     => $client->id,
                'submission_id' => $id,
                'error'         => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to cancel submission. Please try again.');
        }
    }

    // ── Results ────────────────────────────────────────────────────────────────

  public function resultsIndex(Request $request)
{
    $user   = Auth::user();
    $client = $this->clientRepo->findByUserId($user->id);

    $submissions = $client
        ? \App\Models\Kstl\Submission::where('client_id', $client->id)
            ->whereIn('status', [
                \App\Models\Kstl\Submission::STATUS_AUTHORISED,
                \App\Models\Kstl\Submission::STATUS_COMPLETED,
            ])
            ->with(['result.authorisedBy'])
            ->orderByDesc('updated_at')
            ->get()
        : collect();

    Log::info('Client viewed results list', [
        'user_id'   => $user->id,
        'client_id' => $client?->id,
    ]);

    return view('kstl.client.results.index', compact('client', 'user', 'submissions'));
}

    public function resultsShow(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $result = $this->resultRepo->getById($id);

        // Security: only show authorised results to the owning client
        // if ($result->submission->client_id !== $client->id) {
        //     abort(403, 'Unauthorized.');
        // }

        Log::info('Client viewed result', [
            'user_id'   => $user->id,
            'client_id' => $client?->id,
            'result_id' => $id,
        ]);

        return view('kstl.client.results.show', compact('client'));
    }

    public function resultsDownload(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $result = $this->resultRepo->getById($id);

        // Security check
        // if ($result->submission->client_id !== $client->id) {
        //     abort(403, 'Unauthorized.');
        // }

        // if (! $result->report_path || ! Storage::disk('private')->exists($result->report_path)) {
        //     return redirect()->back()->with('error', 'Result report not found.');
        // }

        Log::info('Client downloaded result report', [
            'user_id'   => $user->id,
            'client_id' => $client?->id,
            'result_id' => $id,
        ]);

        // return Storage::disk('private')->download($result->report_path);
    }

    // ── Invoices ───────────────────────────────────────────────────────────────

    public function invoicesIndex(Request $request)
{
    $user   = Auth::user();
    $client = $this->clientRepo->findByUserId($user->id);

    $invoices = $client
        ? \App\Models\Kstl\Invoice::whereHas('submission', fn($q) =>
                $q->where('client_id', $client->id))
            ->with(['items', 'submission'])
            ->orderByDesc('invoice_date')
            ->get()
        : collect();

    Log::info('Client viewed invoices list', [
        'user_id'   => $user->id,
        'client_id' => $client?->id,
    ]);

    return view('kstl.client.invoices.index', compact('client', 'user', 'invoices'));
}

    public function invoicesShow(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $invoice = $this->invoiceRepo->getById($id);

        // Security check
        // if ($invoice->client_id !== $client->id) {
        //     abort(403, 'Unauthorized.');
        // }

        Log::info('Client viewed invoice', [
            'user_id'    => $user->id,
            'client_id'  => $client?->id,
            'invoice_id' => $id,
        ]);

        return view('kstl.client.invoices.show', compact('client'));
    }

    public function invoicesDownload(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $invoice = $this->invoiceRepo->getById($id);

        // Security check
        // if ($invoice->client_id !== $client->id) {
        //     abort(403, 'Unauthorized.');
        // }

        // if (! $invoice->pdf_path || ! Storage::disk('public')->exists($invoice->pdf_path)) {
        //     return redirect()->back()->with('error', 'Invoice not found.');
        // }

        Log::info('Client downloaded invoice', [
            'user_id'    => $user->id,
            'client_id'  => $client?->id,
            'invoice_id' => $id,
        ]);

        // return response()->download(Storage::disk('public')->path($invoice->pdf_path));
    }

    // ── Payments ───────────────────────────────────────────────────────────────

    public function paymentProofShow(string $invoiceId)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $invoice = $this->invoiceRepo->getById($invoiceId);

        // Security check
        // if ($invoice->client_id !== $client->id) {
        //     abort(403, 'Unauthorized.');
        // }

        // if ($invoice->status !== 'unpaid') {
        //     return redirect()->route('client.invoices.show', $invoiceId)
        //         ->with('error', 'This invoice cannot be updated.');
        // }

        return view('kstl.client.payments.proof', compact('client'));
    }

    public function paymentProofStore(Request $request, string $invoiceId)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        try {
            $request->validate([
                'bank_reference_number' => 'required|string|max:255',
                'proof_file'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'payment_method'        => 'nullable|string|in:bank_transfer,mobile_money,cash',
            ]);

            // $invoice = $this->invoiceRepo->getById($invoiceId);

            // Security check
            // if ($invoice->client_id !== $client->id) {
            //     abort(403, 'Unauthorized.');
            // }

            $proofPath = null;
            if ($request->hasFile('proof_file')) {
                $file      = $request->file('proof_file');
                $filename  = 'proof_' . $invoiceId . '_' . time() . '.' . $file->getClientOriginalExtension();
                $proofPath = $file->storeAs('payment_proofs', $filename, 'public');

                Log::info('Payment proof file uploaded', [
                    'user_id'    => $user->id,
                    'client_id'  => $client->id,
                    'invoice_id' => $invoiceId,
                    'file_path'  => $proofPath,
                    'file_size'  => $file->getSize(),
                ]);
            }

            // $this->invoiceRepo->submitPaymentProof($invoice, $request->bank_reference_number, $proofPath);

            Log::info('Client submitted payment proof', [
                'user_id'    => $user->id,
                'client_id'  => $client->id,
                'invoice_id' => $invoiceId,
                'reference'  => $request->bank_reference_number,
            ]);

            return redirect()
                ->route('client.invoices.show', $invoiceId)
                ->with('success', 'Payment proof submitted successfully. It will be verified shortly.');

        } catch (ValidationException $e) {
            Log::warning('Payment proof validation failed', [
                'user_id'    => $user->id,
                'client_id'  => $client->id,
                'invoice_id' => $invoiceId,
                'errors'     => $e->errors(),
            ]);

            throw $e;

        } catch (\Exception $e) {
            Log::error('Payment proof submission failed', [
                'user_id'    => $user->id,
                'client_id'  => $client->id,
                'invoice_id' => $invoiceId,
                'error'      => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to submit payment proof. Please try again.')
                ->withInput();
        }
    }

    public function paymentProofDownload(string $invoiceId)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $invoice = $this->invoiceRepo->getById($invoiceId);

        // Security check
        // if ($invoice->client_id !== $client->id) {
        //     abort(403, 'Unauthorized.');
        // }

        // if (! $invoice->proof_file_path || ! Storage::disk('public')->exists($invoice->proof_file_path)) {
        //     return redirect()->back()->with('error', 'Payment proof not found.');
        // }

        Log::info('Client downloaded payment proof', [
            'user_id'    => $user->id,
            'client_id'  => $client?->id,
            'invoice_id' => $invoiceId,
        ]);

        // return response()->download(Storage::disk('public')->path($invoice->proof_file_path));
    }

    // ── Notifications ──────────────────────────────────────────────────────────

    public function notificationsIndex(Request $request)
    {
        $user = Auth::user();

        \DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $notifications = \DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        Log::info('Client viewed notifications', ['user_id' => $user->id]);

        return view('kstl.client.notifications.index', compact('notifications'));
    }

    public function notificationMarkRead(Request $request, string $id)
    {
        $user = Auth::user();

        \DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->update(['read_at' => now()]);

        Log::info('Client marked notification as read', [
            'user_id'         => $user->id,
            'notification_id' => $id,
        ]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function notificationMarkAllRead(Request $request)
    {
        $user = Auth::user();

        \DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        Log::info('Client marked all notifications as read', ['user_id' => $user->id]);

        return back()->with('success', 'All notifications marked as read.');
    }

    // ── Complaints ─────────────────────────────────────────────────────────────

    public function complaintsIndex(Request $request)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $complaints = $this->complaintRepo->getByClientId($client->id)->paginate(15);

        Log::info('Client viewed complaints list', [
            'user_id'   => $user->id,
            'client_id' => $client?->id,
        ]);

        return view('kstl.client.complaints.index', compact('client'));
    }

    public function complaintsCreate()
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        return view('kstl.client.complaints.create', compact('client'));
    }

    public function complaintsStore(Request $request)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        try {
            $request->validate([
                'subject'     => 'required|string|max:255',
                'description' => 'required|string|max:5000',
            ]);

            DB::transaction(function () use ($request, $client) {
                // $complaint = $this->complaintRepo->create([
                //     'client_id'   => $client->id,
                //     'subject'     => $request->subject,
                //     'description' => $request->description,
                //     'status'      => 'open',
                //     'created_by'  => Auth::id(),
                // ]);

                Log::info('Client lodged complaint', [
                    'user_id'   => Auth::id(),
                    'client_id' => $client->id,
                    'subject'   => $request->subject,
                ]);
            });

            return redirect()
                ->route('client.complaints.index')
                ->with('success', 'Complaint submitted successfully.');

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Exception $e) {
            Log::error('Complaint creation failed', [
                'user_id'   => $user->id,
                'client_id' => $client->id,
                'error'     => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to submit complaint. Please try again.')
                ->withInput();
        }
    }

    public function complaintsShow(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $complaint = $this->complaintRepo->getById($id);

        // Security check
        // if ($complaint->client_id !== $client->id) {
        //     abort(403, 'Unauthorized.');
        // }

        Log::info('Client viewed complaint', [
            'user_id'      => $user->id,
            'client_id'    => $client?->id,
            'complaint_id' => $id,
        ]);

        return view('kstl.client.complaints.show', compact('client'));
    }

    // ── Profile ────────────────────────────────────────────────────────────────

    public function profileShow(Request $request)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        Log::info('Client viewed profile', [
            'user_id'   => $user->id,
            'client_id' => $client?->id,
        ]);

        return view('kstl.client.profile.show', compact('user', 'client'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ]);

            $before = $user->only(['first_name', 'last_name', 'email']);

            $user->update($validated);

            Log::info('Client updated profile', [
                'user_id' => $user->id,
                'before'  => $before,
                'after'   => $validated,
            ]);

            return back()->with('success', 'Profile updated successfully.');

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function profilePasswordUpdate(Request $request)
    {
        $user = Auth::user();

        try {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password'         => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user->update(['password' => $request->password]);

            Log::info('Client changed password', [
                'user_id' => $user->id,
            ]);

            return back()->with('success', 'Password updated successfully.');

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Exception $e) {
            Log::error('Password update failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to update password. Please try again.');
        }
    }

    // ── Document Downloads ─────────────────────────────────────────────────────

    public function documentDownload(string $submissionId, string $documentId)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        // $submission = $this->submissionRepo->getById($submissionId);

        // Security: only the owning client
        // if ($submission->client_id !== $client->id) {
        //     abort(403, 'Unauthorized.');
        // }

        // $document = $this->documentRepo->getById($documentId);

        // Ensure document belongs to this submission
        // if ($document->submission_id !== $submission->id) {
        //     abort(403, 'Document does not belong to this submission.');
        // }

        // if (! Storage::disk('private')->exists($document->file_path)) {
        //     abort(404, 'Document file not found.');
        // }

        Log::info('Client downloaded document', [
            'user_id'       => $user->id,
            'client_id'     => $client?->id,
            'submission_id' => $submissionId,
            'document_id'   => $documentId,
        ]);

        // return Storage::disk('private')->download($document->file_path, $document->original_filename);
    }

    // ── Service Agreement ──────────────────────────────────────────────────────

public function agreementShow(Request $request)
{
    $user   = Auth::user();
    $client = $this->clientRepo->findByUserId($user->id);

    if (! $client) {
        return redirect()->route('client.dashboard')
            ->with('warning', 'Your client profile has not been set up yet. Please contact the lab.');
    }

    // Already signed — redirect to dashboard
    if ($client->service_agreement_signed_at) {
        return redirect()->route('client.dashboard')
            ->with('info', 'You have already signed the service agreement.');
    }

    return view('kstl.client.agreement.show', compact('user', 'client'));
}

public function agreementSign(Request $request)
{
    Log::info('=== Agreement sign attempt started ===', [
        'ip'      => $request->ip(),
        'user_id' => Auth::id(),
    ]);

    $user   = Auth::user();
    $client = $this->clientRepo->findByUserId($user->id);

    if (!$client) {
        return redirect()->route('client.dashboard')
            ->with('error', 'Client profile not found.');
    }

    if ($client->service_agreement_signed_at) {
        return redirect()->route('client.dashboard')
            ->with('info', 'You have already signed the service agreement.');
    }

    try {
       $validated = $request->validate([
            'declaration_accepted'  => 'required|in:1',

            'signature_type'        => 'required|in:drawn,uploaded',
            'signature_data'        => 'required_if:signature_type,drawn|string|nullable',
            'signature_upload'      => 'required_if:signature_type,uploaded|file|mimes:jpg,jpeg,png|max:2048|nullable',
        ]);
        Log::info('Validation passed', [
            'signature_type'      => $validated['signature_type'],
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('Agreement sign failed — validation error', [
            'errors' => $e->errors(),
        ]);
        throw $e;
    }

    // ── Handle signature processing ─────────────────────────────────────
    $signatureData = null;
    $signatureType = $request->signature_type;

    if ($signatureType === 'drawn') {
        $signatureData = $request->signature_data;

        // Extra safety: check it's not an empty canvas
        if (!$signatureData || str_contains($signatureData, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJ') || strlen($signatureData) < 100) {
            return redirect()->back()
                ->with('error', 'Please draw your signature before submitting.')
                ->withInput();
        }

        Log::info('Drawn signature received', [
            'data_length' => strlen($signatureData),
        ]);

    } elseif ($signatureType === 'uploaded' && $request->hasFile('signature_upload')) {
        $file = $request->file('signature_upload');
        $mime = $file->getMimeType();
        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $signatureData = "data:{$mime};base64,{$base64}";

        Log::info('Uploaded signature converted to base64', [
            'mime'        => $mime,
            'data_length' => strlen($signatureData),
        ]);
    }

    if (!$signatureData) {
        return redirect()->back()
            ->with('error', 'Signature is required. Please try again.')
            ->withInput();
    }

    try {
        $result = $this->clientRepo->signServiceAgreement(
            id:                  $client->id,
            signatureData:       $signatureData,
            signatureType:       $signatureType,
            signedIp:            $request->ip(),
            signedUserAgent:     $request->userAgent(),
            filePath:            null,
        );

        Log::info('Service agreement signed successfully', [
            'client_id'      => $client->id,
            'signature_type' => $signatureType,
        ]);

    } catch (\Exception $e) {
        Log::error('Agreement sign failed — repository error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->back()
            ->with('error', 'Failed to save the signed agreement. Please try again.')
            ->withInput();
    }

    $this->auditService->logAgreementSigned($client->fresh());

    return redirect()->route('client.dashboard')
        ->with('success', 'Service agreement signed successfully. You can now submit samples.');
}

// ── Company Profile ────────────────────────────────────────────────────────

public function companyProfileShow(Request $request)
{
    $user   = Auth::user();
    $client = $this->clientRepo->findByUserId($user->id);

    Log::info('Client accessed company profile form', [
        'user_id'         => $user->id,
        'has_profile'     => ! is_null($client),
    ]);

    return view('kstl.client.profile.company', compact('user', 'client'));
}

public function companyProfileStore(Request $request)
{
    $user = Auth::user();

    // Prevent duplicate profiles
    if ($this->clientRepo->existsForUser($user->id)) {
        return redirect()->route('client.agreement.show');
    }

    try {
        $validated = $request->validate([
            'company_name'  => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:1000'],
            'company_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $this->clientRepo->create([
            'user_id'                  => $user->id,
            'company_name'             => $validated['company_name'],
            'address'                  => $validated['address'],
            'company_phone'            => $validated['company_phone'] ?? null,
            'responsible_officer_name' => trim($user->first_name . ' ' . $user->last_name),
        ]);

        Log::info('Client profile created via company form', [
            'user_id'      => $user->id,
            'company_name' => $validated['company_name'],
        ]);

        return redirect()->route('client.agreement.show')
            ->with('success', 'Company details saved. Please read and sign the service agreement.');

    } catch (\Exception $e) {
        Log::error('Company profile creation failed', [
            'user_id' => $user->id,
            'error'   => $e->getMessage(),
        ]);

        return redirect()->back()
            ->with('error', 'Failed to save company details. Please try again.')
            ->withInput();
    }
}

public function companyProfileUpdate(Request $request)
{
    $user   = Auth::user();
    $client = $this->clientRepo->findByUserId($user->id);

    if (! $client) {
        return redirect()->route('client.profile.company.show');
    }

    try {
        $validated = $request->validate([
            'company_name'  => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:1000'],
            'company_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $this->clientRepo->updateById($client->id, [
            'company_name'             => $validated['company_name'],
            'address'                  => $validated['address'],
            'company_phone'            => $validated['company_phone'] ?? null,
            'responsible_officer_name' => trim($user->first_name . ' ' . $user->last_name),
        ]);

        Log::info('Client company profile updated', [
            'user_id'   => $user->id,
            'client_id' => $client->id,
        ]);

        return redirect()->back()
            ->with('success', 'Company details updated successfully.');

    } catch (\Exception $e) {
        Log::error('Company profile update failed', [
            'user_id' => $user->id,
            'error'   => $e->getMessage(),
        ]);

        return redirect()->back()
            ->with('error', 'Failed to update company details. Please try again.')
            ->withInput();
    }
}

public function agreementDownload(Request $request)
{
    $user   = Auth::user();
    $client = $this->clientRepo->findByUserId($user->id);

    if (! $client || ! $client->service_agreement_signed_at) {
        return redirect()->route('client.agreement.show')
            ->with('error', 'You must sign the agreement before downloading it.');
    }

    Log::info('Client downloaded service agreement PDF', [
        'user_id'   => $user->id,
        'client_id' => $client->id,
    ]);

    $pdf = Pdf::loadView('kstl.client.agreement.pdf', compact('user', 'client'))
        ->setPaper('a4', 'portrait');

    $filename = 'KSTL-Service-Agreement-' . str_replace(' ', '-', $client->company_name) . '.pdf';

    return $pdf->download($filename);
}

}