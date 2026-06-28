<?php

namespace App\Mail;

use App\Models\Contact;
use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CustomerConfirmation extends Mailable
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
                'inquiry' => 'Xác nhận đã nhận yêu cầu báo giá',
                'contact' => 'Xác nhận đã nhận liên hệ của bạn',
            },
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.customer-confirmation',
        );
    }
}
