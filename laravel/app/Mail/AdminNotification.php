<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 30;

    public string $title;

    public ?string $subtitle;

    public ?string $badge;

    public ?string $badgeColor;

    public array $dataRows;

    public array $lines;

    public array $meta;

    public ?string $actionText;

    public ?string $actionUrl;

    public ?string $preheader;

    public function __construct(
        string $subject,
        string $title,
        array $dataRows = [],
        array $meta = [],
        ?string $subtitle = null,
        ?string $badge = null,
        ?string $badgeColor = null,
        array $lines = [],
        ?string $actionText = null,
        ?string $actionUrl = null,
        ?string $preheader = null,
    ) {
        $this->subject = $subject;
        $this->title = $title;
        $this->dataRows = $dataRows;
        $this->meta = $meta;
        $this->subtitle = $subtitle;
        $this->badge = $badge;
        $this->badgeColor = $badgeColor;
        $this->lines = $lines;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
        $this->preheader = $preheader;
    }

    public function build(): static
    {
        return $this->subject($this->subject)
            ->view('emails.admin')
            ->with(['preheader' => $this->preheader ?? '']);
    }
}
