<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerEmail extends Mailable
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

    public string $greeting;

    public array $lines;

    public array $outroLines;

    public ?string $actionText;

    public ?string $actionUrl;

    public ?string $highlightBlock;

    public ?string $highlightLabel;

    public ?string $highlightNote;

    public ?string $preheader;

    public function __construct(
        string $subject,
        string $greeting = 'Hello,',
        array $lines = [],
        ?string $actionText = null,
        ?string $actionUrl = null,
        array $outroLines = [],
        ?string $highlightBlock = null,
        ?string $highlightLabel = null,
        ?string $highlightNote = null,
        ?string $preheader = null,
    ) {
        $this->subject = $subject;
        $this->greeting = $greeting;
        $this->lines = $lines;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
        $this->outroLines = $outroLines;
        $this->highlightBlock = $highlightBlock;
        $this->highlightLabel = $highlightLabel;
        $this->highlightNote = $highlightNote;
        $this->preheader = $preheader;
    }

    public function build(): static
    {
        return $this->subject($this->subject)
            ->view('emails.customer')
            ->with(['preheader' => $this->preheader ?? '']);
    }
}
