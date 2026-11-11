<?php

declare(strict_types=1);

use Alibori\LaravelWatson\Jobs\AnalyzeException;
use Alibori\LaravelWatson\LaravelWatson;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();
    config()->set('watson.enabled', true);
    config()->set('watson.route', 'sherlock@bakerstreet.test');
});

test('watson dispatches an analysis of the exception', function () {
    LaravelWatson::handle(new RuntimeException('The butler did it'));

    Queue::assertPushed(AnalyzeException::class, fn (AnalyzeException $job) => $job->route === 'sherlock@bakerstreet.test'
        && $job->exceptionData->class === RuntimeException::class
        && $job->exceptionData->message === 'The butler did it');
});

test('watson stays quiet when disabled', function () {
    config()->set('watson.enabled', false);

    LaravelWatson::handle(new RuntimeException('The butler did it'));

    Queue::assertNothingPushed();
});

test('watson stays quiet without a notification route', function () {
    config()->set('watson.route', null);

    LaravelWatson::handle(new RuntimeException('The butler did it'));

    Queue::assertNothingPushed();
});

test('watson investigates exceptions reported by laravel', function () {
    app(ExceptionHandler::class)->reportable(LaravelWatson::handle(...));

    report(new RuntimeException('The butler did it'));

    Queue::assertPushed(AnalyzeException::class, 1);
});
