<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\Jobs;

use Alibori\LaravelWatson\Ai\Agents\LaravelWatsonAgent;
use Alibori\LaravelWatson\DTO\ExceptionData;
use Alibori\LaravelWatson\DTO\WatsonResult as WatsonResultDTO;
use Alibori\LaravelWatson\Notifications\WatsonResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Laravel\Ai\Responses\StructuredAgentResponse;
use Throwable;

final class AnalyzeException implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ExceptionData $exceptionData,
        public readonly string $route
    ) {}

    public function handle(): void
    {
        try {
            /** @var StructuredAgentResponse $response */
            $response = LaravelWatsonAgent::make()->prompt(
                json_encode($this->exceptionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
            );

            Notification::route(config('watson.channel', 'mail'), $this->route)
                ->notify(new WatsonResult(
                    WatsonResultDTO::fromAgentResponse($response)
                ));
        } catch (Throwable $e) {
            Log::error('Error handling exception with Laravel Watson: '.$e->getMessage(), [
                'original_exception' => $this->exceptionData->toArray(),
                'handling_exception' => $e,
            ]);
        }
    }
}
