<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson;

use Alibori\LaravelWatson\DTO\ExceptionData;
use Alibori\LaravelWatson\Jobs\AnalyzeException;
use Throwable;

final class LaravelWatson
{
    public static function handle(Throwable $exception): void
    {
        if (! config('watson.enabled')) {
            return;
        }

        /** @var string|null $route */
        $route = config('watson.route');

        if (! $route) {
            return;
        }

        AnalyzeException::dispatch(ExceptionData::fromThrowable($exception), $route);
    }
}
