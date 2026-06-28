<?php

namespace App\Mail;

use App\Models\Kstl\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QueryAnalystMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Submission $submission,
        public string     $queryNote,
        public array      $testLabels,
        public bool       $postAuthorisation = false,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Director Query — Action Required — {$this->submission->reference_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.query-analyst',
        );
    }
}
