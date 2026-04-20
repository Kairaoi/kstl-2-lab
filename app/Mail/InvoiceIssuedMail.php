<?php

namespace App\Mail;

use App\Models\Kstl\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceIssuedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Invoice {$this->invoice->invoice_number} — Kiribati Seafood Toxicology Laboratory",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-issued',
        );
    }
}