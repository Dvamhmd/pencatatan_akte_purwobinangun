<?php

namespace App\Mail;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;
    public string $type; // 'birth' or 'death'
    public string $typeLabel;
    public string $status;
    public string $statusLabel;
    public ?string $note;
    public ?string $processedBy;
    public ?string $adminEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $submission,
        string $type,
        string $status,
        ?string $note = null,
        ?string $processedBy = null,
        ?string $adminEmail = null
    ) {
        $this->submission = $submission;
        $this->type = $type;
        $this->typeLabel = ($type === 'birth') ? 'Akte Kelahiran' : 'Akte Kematian';
        $this->status = $status;
        $this->statusLabel = $this->getStatusTitle($status);
        $this->note = $note;
        $this->processedBy = $processedBy ?: 'Petugas Pelayanan Kalurahan';
        $this->adminEmail = $adminEmail;
    }

    private function getStatusTitle(string $status): string
    {
        return match ($status) {
            'ready_for_pickup', 'completed' => 'Siap Diambil di Kantor Kalurahan',
            'revision' => 'Memerlukan Revisi Berkas',
            'rejected' => 'Dibatalkan / Tidak Disetujui',
            'in_process', 'verified' => 'Sedang Diproses',
            'picked_up' => 'Sudah Diambil',
            'archived' => 'Diarsipkan',
            'pending' => 'Menunggu Verifikasi',
            default => 'Pembaruan Status Pengajuan',
        };
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = "[Kalurahan Purwobinangun] {$this->statusLabel} - {$this->typeLabel} ({$this->submission->registration_no})";

        $fromAddress = config('mail.from.address') ?: 'ahmadtaupik580@gmail.com';
        $fromName = config('mail.from.name') ?: 'Pelayanan Kalurahan Purwobinangun';

        $envelope = new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: $subject,
        );

        // Gunakan fromAddress sebagai replyTo untuk mencegah flag phishing/DMARC mismatch dari Gmail/Yahoo
        $envelope->replyTo = [
            new Address($fromAddress, $fromName),
        ];

        return $envelope;
    }

    /**
     * Get the message headers for anti-spam deliverability.
     */
    public function headers(): \Illuminate\Mail\Mailables\Headers
    {
        return new \Illuminate\Mail\Mailables\Headers(
            text: [
                'X-Auto-Response-Suppress' => 'OOF, AutoReply',
                'Auto-Submitted' => 'auto-generated',
                'X-Mailer' => 'Kalurahan Purwobinangun Notification System',
                'X-Priority' => '1',
                'Importance' => 'High',
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.submission-status',
            text: 'emails.submission-status-text',
            with: [
                'submission' => $this->submission,
                'type' => $this->type,
                'typeLabel' => $this->typeLabel,
                'status' => $this->status,
                'statusLabel' => $this->statusLabel,
                'note' => $this->note,
                'processedBy' => $this->processedBy,
                'trackingUrl' => route('tracking.show', [
                    'type' => $this->type === 'birth' ? 'kelahiran' : 'kematian',
                    'registrationNo' => $this->submission->registration_no,
                ]),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
