<?php

namespace App\Mail;

use App\Models\Kstl\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AwaitingAuthorisationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Submission $submission,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Results Awaiting Your Authorisation — {$this->submission->reference_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.awaiting-authorisation',
        );
    }
}