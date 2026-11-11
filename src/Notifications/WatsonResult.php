<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\Notifications;

use Alibori\LaravelWatson\DTO\WatsonResult as DTOWatsonResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Attributes\FailOnTimeout;
use Illuminate\Queue\Attributes\Tries;

#[Tries(3)]
#[FailOnTimeout]
class WatsonResult extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly DTOWatsonResult $watsonResult
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return [config()->string('watson.channel', 'mail')];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("**Laravel Watson result** [{$this->watsonResult->severity}]")
            ->line("**{$this->watsonResult->summary}**")
            ->line("**Why:** {$this->watsonResult->cause}")
            ->line("**Fix:** {$this->watsonResult->fix}");
    }
}
