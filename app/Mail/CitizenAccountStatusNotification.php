<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CitizenAccountStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $citizen;
    public string $actionType; // 'approved', 'rejected', 'deactivated'
    public string $statusLabel;
    public ?string $reason;
    public ?string $processedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(
        User $citizen,
        string $actionType,
        ?string $reason = null,
        ?string $processedBy = null
    ) {
        $this->citizen = $citizen;
        $this->actionType = $actionType;
        $this->reason = $reason;
        $this->processedBy = $processedBy ?: 'Petugas Pelayanan Kalurahan';
        $this->statusLabel = $this->getStatusTitle($actionType);
    }

    private function getStatusTitle(string $actionType): string
    {
        return match ($actionType) {
            'approved' => 'Pendaftaran Akun Disetujui & Diaktifkan',
            'deactivated' => 'Akun Warga Dinonaktifkan',
            'rejected' => 'Pendaftaran Akun Belum Disetujui / Ditolak',
            default => 'Pemberitahuan Status Akun Warga',
        };
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Hindari 16-digit NIK mentah di subjek email agar tidak terpicu filter deteksi PII / phishing
        $subject = "Pemberitahuan: {$this->statusLabel} - Pelayanan Kalurahan Purwobinangun";

        $fromAddress = config('mail.from.address') ?: 'ahmadtaupik580@gmail.com';
        $fromName = config('mail.from.name') ?: 'Pelayanan Kalurahan Purwobinangun';

        $envelope = new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: $subject,
        );

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
        // Hapus X-Priority: 1 dan Importance: High karena merupakan penanda spam paling umum di filter SpamAssassin & Gmail
        return new \Illuminate\Mail\Mailables\Headers(
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
            view: 'emails.citizen-account-status',
            text: 'emails.citizen-account-status-text',
            with: [
                'citizen' => $this->citizen,
                'actionType' => $this->actionType,
                'statusLabel' => $this->statusLabel,
                'reason' => $this->reason,
                'processedBy' => $this->processedBy,
                'loginUrl' => route('warga.login'),
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
