<?php

declare(strict_types=1);

use Alibori\LaravelWatson\DTO\ExceptionData;

test('exception data collects what, where and when', function () {
    $this->freezeTime();

    $line = __LINE__ + 1;
    $exception = new RuntimeException('The butler did it');

    $data = ExceptionData::fromThrowable($exception);

    expect($data)
        ->class->toBe(RuntimeException::class)
        ->message->toBe('The butler did it')
        ->file->toBe(__FILE__)
        ->line->toBe($line)
        ->occurredAt->toBe(now()->toDateTimeString());

    expect($data->trace)->toBeString()->toContain('#0');
});

test('exception data has no request context in console', function () {
    $data = ExceptionData::fromThrowable(new RuntimeException('Silent crime'));

    expect($data)->url->toBeNull()->method->toBeNull()->userId->toBeNull();
});
