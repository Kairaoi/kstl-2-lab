<?php

namespace Database\Seeders;

use App\Models\Kstl\Client;
use App\Models\Kstl\Complaint;
use App\Models\Kstl\Document;
use App\Models\Kstl\DocumentVersion;
use App\Models\Kstl\Invoice;
use App\Models\Kstl\InvoiceItem;
use App\Models\Kstl\Result;
use App\Models\Kstl\Sample;
use App\Models\Kstl\SampleAssessment;
use App\Models\Kstl\SampleTest;
use App\Models\Kstl\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    private User $director;
    private User $reception;
    private User $admin;
    private array $analysts   = [];
    private array $clientUsers = [];
    private array $clients     = [];
    private int   $sampleCounter = 0;

    private const SPECIES = [
        ['common' => 'Yellowfin Tuna',  'scientific' => 'Thunnus albacares'],
        ['common' => 'Skipjack Tuna',   'scientific' => 'Katsuwonus pelamis'],
        ['common' => 'Bigeye Tuna',     'scientific' => 'Thunnus obesus'],
        ['common' => 'Mahimahi',        'scientific' => 'Coryphaena hippurus'],
        ['common' => 'Swordfish',       'scientific' => 'Xiphias gladius'],
        ['common' => 'Giant Clam',      'scientific' => 'Tridacna gigas'],
        ['common' => 'Sea Cucumber',    'scientific' => 'Holothuria scabra'],
        ['common' => 'Rock Lobster',    'scientific' => 'Panulirus penicillatus'],
        ['common' => 'Pacific Grouper', 'scientific' => 'Epinephelus fuscoguttatus'],
        ['common' => 'Milkfish',        'scientific' => 'Chanos chanos'],
    ];

    private const LOCATIONS = [
        'South Tarawa Lagoon', 'North Tarawa', 'Abaiang Atoll', 'Maiana Atoll',
        'Nonouti Atoll', 'Butaritari', 'Marakei', 'Aranuka', 'Abemama',
    ];

    private const MICRO_TESTS = ['total_coliforms', 'e_coli', 'enterococci', 'apc', 'e_coli_coliform', 'staph_aureus', 'yeast_mold'];
    private const CHEM_TESTS  = ['histamine', 'moisture', 'ph', 'conductivity', 'water_activity'];

    // One scenario per status — all 10 submission statuses are represented per client
    private const SCENARIOS = [
        ['status' => 'completed',              'daysAgo' => 60, 'getResult' => true,  'getInvoice' => true,  'invoiceStatus' => 'paid'],
        ['status' => 'completed',              'daysAgo' => 45, 'getResult' => true,  'getInvoice' => true,  'invoiceStatus' => 'paid'],
        ['status' => 'completed',              'daysAgo' => 30, 'getResult' => true,  'getInvoice' => true,  'invoiceStatus' => 'unpaid'],
        ['status' => 'authorised',             'daysAgo' => 14, 'getResult' => true,  'getInvoice' => true,  'invoiceStatus' => 'unpaid'],
        ['status' => 'awaiting_authorisation', 'daysAgo' => 7,  'getResult' => false, 'getInvoice' => false, 'invoiceStatus' => null],
        ['status' => 'testing',                'daysAgo' => 5,  'getResult' => false, 'getInvoice' => false, 'invoiceStatus' => null],
        ['status' => 'accepted',               'daysAgo' => 3,  'getResult' => false, 'getInvoice' => false, 'invoiceStatus' => null],
        ['status' => 'submitted',              'daysAgo' => 1,  'getResult' => false, 'getInvoice' => false, 'invoiceStatus' => null],
        ['status' => 'rejected',               'daysAgo' => 20, 'getResult' => false, 'getInvoice' => false, 'invoiceStatus' => null],
        ['status' => 'cancelled',              'daysAgo' => 15, 'getResult' => false, 'getInvoice' => false, 'invoiceStatus' => null],
    ];

    public function run(): void
    {
        $this->resolveStaff();
        $this->seedExtraClientUsers();
        $this->seedClients();
        $this->seedSubmissions();
        $this->seedComplaints();
        $this->seedDocuments();
        $this->seedLoginAuditTrail();
    }

    // ── 1. Resolve users already created by DatabaseSeeder ───────────────────

    private function resolveStaff(): void
    {
        $this->director  = User::where('email', 'director@example.com')->firstOrFail();
        $this->reception = User::where('email', 'reception@example.com')->firstOrFail();
        $this->admin     = User::where('email', 'test@example.com')->firstOrFail();

        $analyst1 = User::where('email', 'analyst@example.com')->firstOrFail();
        $analyst2 = User::firstOrCreate(
            ['email' => 'analyst2@example.com'],
            ['first_name' => 'Taina', 'last_name' => 'Kamoriki', 'password' => Hash::make('1'), 'email_verified_at' => now()]
        );
        if (! $analyst2->hasRole('analyst')) {
            $analyst2->assignRole('analyst');
        }
        $this->analysts = [$analyst1, $analyst2];

        $this->clientUsers[] = User::where('email', 'client@example.com')->firstOrFail();
    }

    // ── 2. Two additional client-role users ───────────────────────────────────

    private function seedExtraClientUsers(): void
    {
        foreach ([
            ['first_name' => 'Tebuka', 'last_name' => 'Toatu', 'email' => 'tebuka@pacificfisheries.ki'],
            ['first_name' => 'Nei',    'last_name' => 'Rimon', 'email' => 'nei@southpacific.ki'],
        ] as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => Hash::make('1'), 'email_verified_at' => now()])
            );
            if (! $user->hasRole('client')) {
                $user->assignRole('client');
            }
            $this->clientUsers[] = $user;
        }
    }

    // ── 3. Client company records ─────────────────────────────────────────────

    private function seedClients(): void
    {
        $companies = [
            ['company_name' => 'Kiribati Ocean Products',    'address' => 'PO Box 12, Betio, South Tarawa, Kiribati',             'company_phone' => '+686 21234', 'responsible_officer_name' => 'John Smith'],
            ['company_name' => 'Pacific Fisheries Ltd',      'address' => 'Lot 7, Industrial Zone, Betio, South Tarawa, Kiribati', 'company_phone' => '+686 25678', 'responsible_officer_name' => 'Tebuka Toatu'],
            ['company_name' => 'South Pacific Seafoods Co.', 'address' => 'Bikenibeu, South Tarawa, Kiribati',                    'company_phone' => '+686 29012', 'responsible_officer_name' => 'Nei Rimon'],
        ];

        foreach ($this->clientUsers as $i => $user) {
            $company = $companies[$i] ?? $companies[0];
            $this->clients[] = Client::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($company, [
                    'service_agreement_signed_at' => now()->subMonths(rand(3, 10)),
                    'director_signed_by'           => trim($this->director->first_name . ' ' . $this->director->last_name),
                    'director_signed_by_id'         => $this->director->id,
                    'director_signed_at'            => now()->subMonths(rand(2, 9)),
                ])
            );
        }
    }

    // ── 4. Submissions — full workflow per scenario ───────────────────────────

    private function seedSubmissions(): void
    {
        foreach ($this->clients as $client) {
            foreach (self::SCENARIOS as $scenario) {
                $this->makeSubmission($client, $scenario);
            }
        }
    }

    private function makeSubmission(Client $client, array $scenario): void
    {
        $species     = $this->pick(self::SPECIES);
        $submittedAt = now()->subDays($scenario['daysAgo'] + 1);
        $receivedAt  = $submittedAt->copy()->addHours(rand(2, 24));
        $testKeys    = $this->pickMany(array_merge(self::MICRO_TESTS, self::CHEM_TESTS), 3, 5);
        $status      = $scenario['status'];

        $transportMethod = $this->pick(['frozen', 'chilled', 'fresh']);
        $transportDetail = $this->pickTransportDetail($transportMethod);

        $submission = Submission::create([
            'reference_number'    => Submission::generateReference(),
            'client_id'           => $client->id,
            'received_by'         => $this->reception->id,
            'sample_name'         => $species['common'],
            'scientific_name'     => $species['scientific'],
            'sample_description'  => "Fresh {$species['common']} for export quality testing.",
            'sample_type'         => $this->pick(['fish', 'fish', 'shellfish', 'water']),
            'sample_quantity'     => rand(5, 50),
            'sample_quantity_unit'=> $this->pick(['kg', 'g']),
            'collected_at'        => $submittedAt->copy()->subDays(rand(1, 3))->toDateString(),
            'collection_location' => $this->pick(self::LOCATIONS),
            'tests_requested'     => $testKeys,
            'transport_method'    => $transportMethod,
            'transport_detail'    => $transportDetail,
            'priority'            => $this->pick(['routine', 'routine', 'routine', 'urgent', 'emergency']),
            'results_required_by' => $submittedAt->copy()->addDays(14)->toDateString(),
            'service_mode'        => 'lab_to_client',
            'application_date'    => $submittedAt->toDateString(),
            'submitter_name'      => $client->responsible_officer_name,
            'submitted_at'        => $submittedAt,
            'received_at'         => $status === 'submitted' ? null : $receivedAt,
            'status'              => $status,
            'client_notes'        => 'Please prioritise export certification.',
        ]);

        $samples = [];
        for ($i = 0; $i < rand(1, 2); $i++) {
            $samples[] = $this->makeSample($submission, $species, $status, $testKeys);
        }

        $result = null;
        if ($scenario['getResult']) {
            $result = $this->makeResult($submission, $scenario['daysAgo']);
        }

        if ($scenario['getInvoice'] && $result !== null) {
            $sampleIds = collect($samples)->pluck('id')->toArray();
            $tests = SampleTest::whereIn('sample_id', $sampleIds)->get();
            $this->makeInvoice($submission, $result, $client, $tests, $scenario['invoiceStatus'], $scenario['daysAgo']);
        }
    }

    // ── Sample ────────────────────────────────────────────────────────────────

    private function makeSample(Submission $submission, array $species, string $submissionStatus, array $testKeys): Sample
    {
        $this->sampleCounter++;

        $sampleStatus = match(true) {
            in_array($submissionStatus, ['submitted', 'received', 'assessing']) => 'pending',
            in_array($submissionStatus, ['rejected', 'cancelled'])              => 'rejected',
            in_array($submissionStatus, ['accepted', 'consent_to_proceed'])     => 'accepted',
            in_array($submissionStatus, ['testing', 'awaiting_authorisation'])  => 'testing',
            in_array($submissionStatus, ['authorised', 'completed'])            => 'completed',
            default                                                              => 'pending',
        };

        $sample = Sample::create([
            'submission_id'  => $submission->id,
            'sample_code'    => 'KSTL-S-' . str_pad($this->sampleCounter, 5, '0', STR_PAD_LEFT),
            'sampling_date'  => $submission->collected_at,
            'common_name'    => $species['common'],
            'scientific_name'=> $species['scientific'],
            'quantity'       => rand(1, 20),
            'quantity_unit'  => 'kg',
            'status'         => $sampleStatus,
        ]);

        if ($submissionStatus !== 'submitted') {
            $this->makeAssessment($sample, $submissionStatus);
        }

        if (! in_array($submissionStatus, ['submitted', 'received', 'assessing', 'rejected', 'cancelled'])) {
            $this->makeTests($sample, $submissionStatus, $testKeys);
        }

        return $sample;
    }

    // ── Sample Assessment ─────────────────────────────────────────────────────

    private function makeAssessment(Sample $sample, string $submissionStatus): void
    {
        $isRejected = $submissionStatus === 'rejected';
        $outcome    = $isRejected
            ? 'rejected'
            : $this->pick(['accepted', 'accepted', 'accepted_with_note']);

        SampleAssessment::create([
            'sample_id'                   => $sample->id,
            'assessed_by'                 => $this->reception->id,
            'assessed_at'                 => now()->subDays(rand(1, 5)),
            'temperature_ok'              => ! $isRejected,
            'temperature_notes'           => $isRejected ? 'Temperature outside acceptable range on arrival.' : null,
            'storage_ok'                  => true,
            'transport_ok'                => true,
            'packaging_ok'                => true,
            'colour_ok'                   => true,
            'odour_ok'                    => true,
            'weight_ok'                   => true,
            'outcome'                     => $outcome,
            'rejection_reason'            => $outcome === 'rejected' ? 'Cold chain integrity not maintained during transport.' : null,
            'client_decision'             => $outcome === 'rejected' ? $this->pick(['confirm_rejection', 'consent_to_proceed']) : null,
            'client_decision_at'          => $outcome === 'rejected' ? now()->subDays(rand(1, 3)) : null,
            'client_decision_recorded_by' => $outcome === 'rejected' ? $this->reception->id : null,
            'consent_method'              => $outcome === 'rejected' ? 'manual' : null,
        ]);
    }

    // ── Sample Tests ──────────────────────────────────────────────────────────

    private function makeTests(Sample $sample, string $submissionStatus, array $testKeys): void
    {
        $testStatus = match(true) {
            in_array($submissionStatus, ['accepted', 'consent_to_proceed'])            => 'queued',
            $submissionStatus === 'testing'                                             => 'in_progress',
            in_array($submissionStatus, ['awaiting_authorisation', 'authorised', 'completed']) => 'completed',
            default => 'queued',
        };

        foreach ($testKeys as $testKey) {
            $done    = $testStatus === 'completed';
            $analyst = $this->pick($this->analysts);

            SampleTest::create([
                'sample_id'          => $sample->id,
                'test_key'           => $testKey,
                'test_label'         => SampleTest::TEST_LABELS[$testKey] ?? ucfirst(str_replace('_', ' ', $testKey)),
                'test_category'      => SampleTest::TEST_CATEGORIES[$testKey] ?? 'microbiological',
                'assigned_to'        => $analyst->id,
                'price_aud_snapshot' => Invoice::TEST_PRICES[$testKey] ?? 75.00,
                'result_value'       => $done ? $this->fakeValue($testKey)     : null,
                'result_unit'        => $done ? $this->fakeUnit($testKey)      : null,
                'result_qualifier'   => $done ? $this->fakeQualifier($testKey) : 'pending',
                'result_notes'       => $done ? $this->pick([null, null, 'Tested in duplicate; results concordant.', 'Within acceptable limits per Codex standard.']) : null,
                'started_at'         => ($done || $testStatus === 'in_progress') ? now()->subDays(rand(1, 5)) : null,
                'completed_at'       => $done ? now()->subDays(rand(0, 2)) : null,
                'status'             => $testStatus,
            ]);
        }
    }

    // ── Result ────────────────────────────────────────────────────────────────

    private function makeResult(Submission $submission, int $daysAgo): Result
    {
        return Result::create([
            'submission_id'     => $submission->id,
            'authorised_by'     => $this->director->id,
            'overall_outcome'   => $this->pick(['pass', 'pass', 'pass', 'fail', 'inconclusive']),
            'director_comments' => 'All parameters reviewed and verified. Results consistent with export quality standards.',
            'authorised_at'     => now()->subDays(max(1, $daysAgo - 3)),
            'client_notified_at'=> now()->subDays(max(0, $daysAgo - 4)),
        ]);
    }

    // ── Invoice + Line Items ──────────────────────────────────────────────────

    private function makeInvoice(Submission $submission, Result $result, Client $client, $tests, string $paymentStatus, int $daysAgo): void
    {
        $invoiceDate = now()->subDays(max(1, $daysAgo - 2));
        $dueDate     = Invoice::calculateDueDate($invoiceDate);
        $isPaid      = $paymentStatus === 'paid';

        $lineItems = [];
        $total     = 0;

        foreach ($tests as $test) {
            $price = (float) ($test->price_aud_snapshot ?? 75.00);
            $lineItems[] = [
                'sample_test_id'   => $test->id,
                'item_description' => $test->test_label ?? ucfirst(str_replace('_', ' ', $test->test_key)),
                'category'         => ucfirst($test->test_category ?? 'General'),
                'unit_price_aud'   => $price,
                'quantity'         => 1,
                'total_price_aud'  => $price,
            ];
            $total += $price;
        }

        if (empty($lineItems)) {
            $lineItems[] = ['sample_test_id' => null, 'item_description' => 'Laboratory Testing Services', 'category' => 'General', 'unit_price_aud' => 250.00, 'quantity' => 1, 'total_price_aud' => 250.00];
            $total = 250.00;
        }

        $invoice = Invoice::create([
            'invoice_number'      => Invoice::generateNumber(),
            'submission_id'       => $submission->id,
            'result_id'           => $result->id,
            'issued_by'           => $this->admin->id,
            'bill_to_company'     => $client->company_name,
            'bill_to_address'     => $client->address,
            'bill_to_phone'       => $client->company_phone,
            'total_amount_aud'    => $total,
            'invoice_date'        => $invoiceDate->toDateString(),
            'payment_due_date'    => $dueDate->toDateString(),
            'payment_status'      => $paymentStatus,
            'payment_reference'   => $isPaid ? 'REF-' . strtoupper(Str::random(6)) : null,
            'payment_received_at' => $isPaid ? now()->subDays(max(1, $daysAgo - 10)) : null,
            'payment_verified_by' => $isPaid ? $this->admin->id : null,
        ]);

        foreach ($lineItems as $item) {
            InvoiceItem::create(array_merge(['invoice_id' => $invoice->id], $item));
        }
    }

    // ── Complaints ────────────────────────────────────────────────────────────

    private function seedComplaints(): void
    {
        $subs = Submission::where('status', 'completed')->take(2)->get();

        $rows = [
            [
                'complainant_name'         => 'Timon Anote',
                'complainant_email'        => 'tanote@pacificfisheries.ki',
                'complainant_organisation' => 'Pacific Fisheries Ltd',
                'incident_date'            => now()->subDays(25)->toDateString(),
                'subject'                  => 'Delay in test result delivery',
                'complaint_types'          => ['delay_in_results'],
                'description'              => 'Results for our submission were delivered 5 days past the agreed turnaround time, causing delays in our export shipment to Australia.',
                'status'                   => 'resolved',
                'lab_response'             => 'We sincerely apologise for the delay. The extended turnaround was due to equipment calibration issues that have since been rectified.',
                'action_taken'             => 'Staff training on equipment maintenance updated. Priority escalation procedure reviewed and strengthened.',
                'resolved_at'             => now()->subDays(20),
                'submission_id'            => $subs->get(0)?->id,
            ],
            [
                'complainant_name'         => 'Nei Tiaon',
                'complainant_email'        => 'ntiaon@southpacific.ki',
                'complainant_organisation' => 'South Pacific Seafoods Co.',
                'incident_date'            => now()->subDays(10)->toDateString(),
                'subject'                  => 'Unclear sample rejection notice',
                'complaint_types'          => ['poor_customer_service', 'other'],
                'description'              => 'The rejection notice did not clearly identify which quality criterion failed or provide guidance for resubmission.',
                'status'                   => 'under_investigation',
                'lab_response'             => null,
                'action_taken'             => null,
                'resolved_at'             => null,
                'submission_id'            => $subs->get(1)?->id,
            ],
            [
                'complainant_name'         => 'Ioane Temai',
                'complainant_email'        => 'itemai@example.com',
                'complainant_organisation' => null,
                'incident_date'            => now()->subDays(3)->toDateString(),
                'subject'                  => 'Possible transcription error in histamine result',
                'complaint_types'          => ['other', 'billing'],
                'description'              => 'The histamine value on the issued certificate (48.2 mg/kg) does not match the raw instrument data we observed (4.82 mg/kg). Requesting investigation.',
                'status'                   => 'open',
                'lab_response'             => null,
                'action_taken'             => null,
                'resolved_at'             => null,
                'submission_id'            => null,
            ],
        ];

        foreach ($rows as $data) {
            Complaint::create([
                'complainant_name'         => $data['complainant_name'],
                'complainant_email'        => $data['complainant_email'],
                'complainant_organisation' => $data['complainant_organisation'],
                'incident_date'            => $data['incident_date'],
                'subject'                  => $data['subject'],
                'complaint_types'          => $data['complaint_types'],
                'description'              => $data['description'],
                'submission_id'            => $data['submission_id'],
                'assigned_to'              => $this->admin->id,
                'lab_response'             => $data['lab_response'],
                'action_taken'             => $data['action_taken'],
                'resolved_by'              => $data['resolved_at'] ? $this->director->id : null,
                'resolved_at'             => $data['resolved_at'],
                'status'                   => $data['status'],
            ]);
        }
    }

    // ── Documents + Versions ──────────────────────────────────────────────────

    private function seedDocuments(): void
    {
        $docs = [
            [
                'title' => 'Microbiology Testing SOP — APC & Coliforms',
                'category' => 'sop', 'subcategory' => 'Microbiology', 'reference_code' => 'SOP-MICRO-001',
                'description' => 'SOP for aerobic plate count and total coliform testing using Petrifilm.',
                'versions' => [
                    [1, 'SOP-MICRO-001-v1.pdf', 'Initial release.'],
                    [2, 'SOP-MICRO-001-v2.pdf', 'Updated incubation temperatures to align with ISO 4833-1:2013.'],
                ],
            ],
            [
                'title' => 'Histamine Rapid Kit Testing SOP',
                'category' => 'sop', 'subcategory' => 'Chemical', 'reference_code' => 'SOP-CHEM-002',
                'description' => 'SOP for histamine analysis in fish products using ELISA rapid kit.',
                'versions' => [
                    [1, 'SOP-CHEM-002-v1.pdf', 'Initial release.'],
                ],
            ],
            [
                'title' => 'Quality Manual — ISO/IEC 17025:2017',
                'category' => 'manual', 'subcategory' => 'Quality System', 'reference_code' => 'QM-001',
                'description' => 'KSTL laboratory quality management system manual.',
                'versions' => [
                    [1, 'QM-001-v1.pdf', 'Initial issue.'],
                    [2, 'QM-001-v2.pdf', 'Section 6 revised — updated equipment management procedures.'],
                    [3, 'QM-001-v3.pdf', 'Annual review. No substantive policy changes.'],
                ],
            ],
            [
                'title' => 'Sample Receipt & Assessment Procedure',
                'category' => 'sop', 'subcategory' => 'Reception', 'reference_code' => 'SOP-RECV-001',
                'description' => 'Procedure for receipt, logging, and quality assessment of incoming samples.',
                'versions' => [
                    [1, 'SOP-RECV-001-v1.pdf', 'Initial release.'],
                ],
            ],
            [
                'title' => 'Schedule 1 — Sample Submission Form Template',
                'category' => 'template', 'subcategory' => 'Forms', 'reference_code' => 'TMPL-SCHED1',
                'description' => 'Client-facing sample submission application form (Schedule 1).',
                'versions' => [
                    [1, 'Schedule1-v1.docx', 'Initial template.'],
                    [2, 'Schedule1-v2.docx', 'Added export certification checkbox; updated contact details.'],
                ],
            ],
        ];

        foreach ($docs as $data) {
            $doc = Document::create([
                'title'          => $data['title'],
                'category'       => $data['category'],
                'subcategory'    => $data['subcategory'],
                'reference_code' => $data['reference_code'],
                'description'    => $data['description'],
                'created_by'     => $this->admin->id,
            ]);

            $latest = null;
            foreach ($data['versions'] as [$vNum, $filename, $note]) {
                $latest = DocumentVersion::create([
                    'document_id'       => $doc->id,
                    'version_number'    => $vNum,
                    'original_filename' => $filename,
                    'file_path'         => "documents/{$doc->id}/{$filename}",
                    'mime_type'         => str_ends_with($filename, '.pdf')
                                            ? 'application/pdf'
                                            : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'file_size'         => rand(80_000, 450_000),
                    'change_note'       => $note,
                    'uploaded_by'       => $this->admin->id,
                ]);
            }

            if ($latest) {
                $doc->update(['current_version_id' => $latest->id]);
            }
        }
    }

    // ── Audit trail — login/logout history ───────────────────────────────────

    private function seedLoginAuditTrail(): void
    {
        $users = User::all();

        $ipCountries = [
            '203.101.45.12' => ['code' => 'AU', 'name' => 'Australia'],
            '202.72.178.44' => ['code' => 'FJ', 'name' => 'Fiji'],
            '192.168.1.10'  => ['code' => 'KI', 'name' => 'Kiribati'],
            '127.0.0.1'     => ['code' => 'KI', 'name' => 'Kiribati'],
            '58.96.42.111'  => ['code' => 'NZ', 'name' => 'New Zealand'],
        ];
        $ips = array_keys($ipCountries);

        for ($i = 0; $i < 40; $i++) {
            $user    = $users->random();
            $isFail  = rand(1, 6) === 1;
            $event   = $isFail ? 'login_failed' : $this->pick(['login', 'login', 'login', 'logout']);
            $ip      = $this->pick($ips);
            $country = $ipCountries[$ip];
            $name    = trim($user->first_name . ' ' . $user->last_name);

            DB::table('audit_logs')->insert([
                'id'           => Str::uuid()->toString(),
                'user_id'      => $isFail ? null : $user->id,
                'user_name'    => $isFail ? $user->email : $name,
                'event'        => $event,
                'description'  => match($event) {
                    'login'        => "{$name} logged in",
                    'logout'       => "{$name} logged out",
                    'login_failed' => "Failed login attempt for {$user->email}",
                    default        => $event,
                },
                'ip_address'   => $ip,
                'country_code' => $country['code'],
                'country_name' => $country['name'],
                'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0',
                'created_at'   => now()->subDays(rand(0, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            ]);
        }
    }

    // ── Result value helpers ──────────────────────────────────────────────────

    private function fakeValue(string $key): string
    {
        return match(true) {
            in_array($key, ['total_coliforms', 'e_coli', 'e_coli_coliform']) => (string) rand(0, 500),
            $key === 'apc'                                                    => (string) rand(1_000, 50_000),
            in_array($key, ['enterococci', 'staph_aureus'])                   => (string) rand(0, 100),
            $key === 'yeast_mold'                                              => (string) rand(0, 200),
            $key === 'histamine'                                               => number_format(rand(10, 500) / 10, 1),
            $key === 'moisture'                                                => number_format(rand(600, 800) / 10, 1),
            $key === 'ph'                                                      => number_format(rand(60, 75) / 10, 1),
            $key === 'conductivity'                                            => (string) rand(100, 500),
            $key === 'water_activity'                                          => number_format(rand(85, 99) / 100, 2),
            default                                                            => 'ND',
        };
    }

    private function fakeUnit(string $key): ?string
    {
        return match(true) {
            in_array($key, ['total_coliforms', 'e_coli', 'e_coli_coliform', 'enterococci', 'faecal_coliforms', 'staph_aureus', 'apc', 'yeast_mold', 'salmonella_spp', 'listeria_mono', 'listeria_spp']) => 'CFU/g',
            in_array($key, ['e_coli_colilert', 'enterococci_enterolert']) => 'MPN/100mL',
            $key === 'histamine'    => 'mg/kg',
            $key === 'moisture'     => '%',
            $key === 'conductivity' => 'µS/cm',
            default                 => null,
        };
    }

    private function fakeQualifier(string $key): string
    {
        // Coliform/pathogen tests: mostly not_detected, occasionally a numeric count
        if (in_array($key, ['total_coliforms', 'e_coli', 'e_coli_coliform'])) {
            return $this->pick(['not_detected', 'not_detected', 'equal_to', 'equal_to']);
        }
        if (in_array($key, ['enterococci', 'staph_aureus'])) {
            return $this->pick(['not_detected', 'not_detected', 'not_detected', 'equal_to']);
        }
        // Plate counts: always numeric (CFU/g value)
        if (in_array($key, ['apc', 'yeast_mold'])) {
            return 'equal_to';
        }
        // Chemical: moisture tends to be less_than, pH and water_activity equal_to
        if ($key === 'moisture') {
            return $this->pick(['less_than', 'less_than', 'equal_to']);
        }
        if ($key === 'ph') {
            return $this->pick(['equal_to', 'equal_to', 'less_than']);
        }
        return $this->pick(['less_than', 'equal_to']);
    }

    private function pickTransportDetail(string $method): string
    {
        return match($method) {
            'frozen'  => $this->pick(['Air freight (Frozen)', 'Road Transport (Frozen truck)', 'Sea freight (Frozen container)']),
            'chilled' => $this->pick(['Air freight (Chilled)', 'Road transport (Chilled van)', 'Sea freight (Chilled)']),
            default   => $this->pick(['Air freight', 'Road transport (Fresh/Iced)', 'Express courier (Iced)']),
        };
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function pick(array $items): mixed
    {
        return $items[array_rand($items)];
    }

    private function pickMany(array $items, int $min, int $max): array
    {
        $count = rand($min, min($max, count($items)));
        $keys  = (array) array_rand($items, $count);
        return array_map(fn ($k) => $items[$k], $keys);
    }
}
