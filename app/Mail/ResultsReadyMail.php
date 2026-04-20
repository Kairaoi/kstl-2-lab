<?php

namespace App\Mail;

use App\Models\Kstl\Result;
use App\Models\Kstl\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResultsReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Submission $submission,
        public Result     $result,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Test Results Are Ready — {$this->submission->reference_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.results-ready',
        );
    }
}