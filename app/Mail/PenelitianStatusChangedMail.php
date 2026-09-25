<?php

namespace App\Mail;

use App\Models\Penelitian;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenelitianStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Penelitian $penelitian
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Perubahan Status Penelitian: ' . $this->penelitian->judul_penelitian,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penelitian.status_changed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
