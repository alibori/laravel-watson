<?php

declare(strict_types=1);

use Alibori\LaravelWatson\LaravelWatson;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Log;

beforeEach(fn () => Log::spy());

test('watson logs the exception data', function () {
    LaravelWatson::handle(new RuntimeException('The butler did it'));

    Log::shouldHaveReceived('info')->once()->withArgs(
        fn (string $message, array $context) => $context['class'] === RuntimeException::class
            && $context['message'] === 'The butler did it'
    );
});

test('watson stays quiet when disabled', function () {
    config()->set('watson.enabled', false);

    LaravelWatson::handle(new RuntimeException('The butler did it'));

    Log::shouldNotHaveReceived('info');
});

test('watson investigates exceptions reported by laravel', function () {
    app(ExceptionHandler::class)->reportable(LaravelWatson::handle(...));

    report(new RuntimeException('The butler did it'));

    Log::shouldHaveReceived('info')->once();
});
