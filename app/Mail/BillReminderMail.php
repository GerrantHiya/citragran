<?php

namespace App\Mail;

use App\Models\Resident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BillReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Resident $resident;
    public $unpaidBills;
    public $totalOutstanding;

    /**
     * Create a new message instance.
     */
    public function __construct(Resident $resident)
    {
        $this->resident = $resident;
        $this->unpaidBills = $resident->unpaid_bills;
        $this->totalOutstanding = $resident->total_outstanding;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengingat Tagihan IPL - Perumahan Citra Gran',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bill-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
