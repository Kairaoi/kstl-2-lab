<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Models\Kstl\Invoice;
use App\Models\Kstl\SampleTest;
use App\Models\Kstl\Submission;
use App\Repositories\Kstl\ClientRepository;
use App\Repositories\Kstl\SampleRepository;
use App\Repositories\Kstl\SampleTestRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientResultController extends Controller
{
    public function __construct(
        protected ClientRepository     $clientRepo,
        protected SampleRepository     $sampleRepo,
        protected SampleTestRepository $testRepo,
    ) {}

    public function index()
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $submissions = $client
            ? Submission::where('client_id', $client->id)
                ->whereIn('status', [
                    Submission::STATUS_AUTHORISED,
                    Submission::STATUS_COMPLETED,
                ])
                ->with(['result.authorisedBy', 'invoice'])
                ->orderByDesc('submitted_at')
                ->get()
            : collect();

        Log::info('Client viewed results list', [
            'user_id'   => $user->id,
            'client_id' => $client?->id,
        ]);

        return view('kstl.client.results.index',
            compact('client', 'user', 'submissions'));
    }

    public function show(string $submissionId)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        abort_if(! $client, 403);

        $submission = Submission::where('id', $submissionId)
            ->where('client_id', $client->id)
            ->whereIn('status', [
                Submission::STATUS_AUTHORISED,
                Submission::STATUS_COMPLETED,
            ])
            ->with(['result.authorisedBy', 'samples', 'invoice'])
            ->firstOrFail();

        // Payment gate — results are withheld until the invoice is paid
        $invoice = $submission->invoice;
        if (! $invoice) {
            return redirect()->route('client.invoices.index')
                ->with('info', 'Your invoice for ' . $submission->reference_number . ' is being prepared. Results will be available once payment is received.');
        }
        if (! $invoice->isPaid() && ! $invoice->isWaived()) {
            return redirect()->route('client.invoices.show', $invoice->id)
                ->with('warning', 'Your results for ' . $submission->reference_number . ' are ready, but will only be released once payment is confirmed. Please settle the invoice below.');
        }

        $result  = $submission->result;
        $samples = $submission->samples;

        $testsBySample = [];
        foreach ($samples as $sample) {
            $testsBySample[$sample->id] = SampleTest::where('sample_id', $sample->id)
                ->orderBy('test_category')
                ->orderBy('test_key')
                ->get();
        }

        Log::info('Client viewed result', [
            'user_id'       => $user->id,
            'client_id'     => $client->id,
            'submission_id' => $submissionId,
        ]);

        return view('kstl.client.results.show',
            compact('submission', 'result', 'samples', 'testsBySample', 'client', 'user'));
    }
}