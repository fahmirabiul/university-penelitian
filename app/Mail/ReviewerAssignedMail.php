<?php

namespace App\Mail;

use App\Models\Penelitian;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewerAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Penelitian $penelitian
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penugasan Review Proposal: ' . $this->penelitian->judul_penelitian,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penelitian.reviewer_assigned',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
