<?php

namespace App\Mail;

use App\Models\Contact;
use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AdminNotification extends Mailable
{
    use Queueable;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Inquiry|Contact $submission,
        public string $type, // 'inquiry' or 'contact'
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match ($this->type) {
                'inquiry' => 'Yêu cầu báo giá mới từ ' . $this->submission->name,
                'contact' => 'Liên hệ mới từ ' . $this->submission->name,
            },
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.admin-notification',
        );
    }
}
