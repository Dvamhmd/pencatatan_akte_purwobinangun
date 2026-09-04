<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class AdminNewSubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public string $submissionType;
    public string $typeTitle;
    public string $subjectLine;
    public string $actionUrl;
    public array $details;
    public mixed $submission;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $submissionType,
        string $typeTitle,
        string $subjectLine,
        string $actionUrl,
        array $details,
        mixed $submission
    ) {
        $this->submissionType = $submissionType;
        $this->typeTitle = $typeTitle;
        $this->subjectLine = $subjectLine;
        $this->actionUrl = $actionUrl;
        $this->details = $details;
        $this->submission = $submission;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address') ?: 'ahmadtaupik580@gmail.com';
        $fromName = config('mail.from.name') ?: 'Pelayanan Kalurahan Purwobinangun';

        $envelope = new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: $this->subjectLine,
        );

        $envelope->replyTo = [
            new Address($fromAddress, $fromName),
        ];

        return $envelope;
    }

    /**
     * Get the message headers for anti-spam deliverability.
     */
    public function headers(): Headers
    {
        return new Headers(
            text: [
                'X-Auto-Response-Suppress' => 'OOF, AutoReply',
                'Auto-Submitted' => 'auto-generated',
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-submission',
            text: 'emails.admin-new-submission-text',
            with: [
                'submissionType' => $this->submissionType,
                'typeTitle' => $this->typeTitle,
                'actionUrl' => $this->actionUrl,
                'details' => $this->details,
                'submission' => $this->submission,
            ],
        );
    }
}
