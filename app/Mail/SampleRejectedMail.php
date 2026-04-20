<?php

namespace App\Mail;

use App\Models\Kstl\Sample;
use App\Models\Kstl\SampleAssessment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SampleRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $consentUrl;

    public function __construct(
        public Sample           $sample,
        public SampleAssessment $assessment,
    ) {
        $this->consentUrl = route('client.consent.show', [
            'token' => $assessment->consent_token,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Sample Assessment Result — {$this->sample->submission->reference_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sample-rejected',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}