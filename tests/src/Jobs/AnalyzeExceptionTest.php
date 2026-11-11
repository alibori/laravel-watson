<?php

declare(strict_types=1);

use Alibori\LaravelWatson\Ai\Agents\LaravelWatsonAgent;
use Alibori\LaravelWatson\DTO\ExceptionData;
use Alibori\LaravelWatson\Jobs\AnalyzeException;
use Alibori\LaravelWatson\Notifications\WatsonResult;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Laravel\Ai\AiServiceProvider;

beforeEach(function () {
    $this->app->register(AiServiceProvider::class);
    Notification::fake();
    config()->set('watson.channel', 'mail');

    $this->job = new AnalyzeException(
        ExceptionData::fromThrowable(new RuntimeException('The butler did it')),
        'sherlock@bakerstreet.test',
    );
});

test('job asks the agent and notifies the diagnosis', function () {
    LaravelWatsonAgent::fake([[
        'summary' => 'Null user',
        'cause' => 'Guest request',
        'fix' => 'Add auth middleware',
        'severity' => 'high',
    ]]);

    $this->job->handle();

    LaravelWatsonAgent::assertPrompted(fn ($prompt) => str_contains($prompt->prompt, 'The butler did it')
        && str_contains($prompt->prompt, RuntimeException::class));

    Notification::assertSentOnDemand(
        WatsonResult::class,
        fn (WatsonResult $notification, array $channels, AnonymousNotifiable $notifiable) => $notifiable->routes['mail'] === 'sherlock@bakerstreet.test'
            && $notification->watsonResult->summary === 'Null user'
            && $notification->watsonResult->severity === 'high'
    );
});

test('job logs and swallows agent failures', function () {
    Log::spy();
    LaravelWatsonAgent::fake(fn () => throw new RuntimeException('AI is down'));

    $this->job->handle();

    Log::shouldHaveReceived('error')->once()->withArgs(
        fn (string $message, array $context) => str_contains($message, 'AI is down')
            && $context['original_exception']['message'] === 'The butler did it'
            && $context['handling_exception']->getMessage() === 'AI is down'
    );
    Notification::assertNothingSent();
});
