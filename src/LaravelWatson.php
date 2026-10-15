<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson;

use Alibori\LaravelWatson\DTO\ExceptionData;
use Illuminate\Support\Facades\Log;
use Throwable;

class LaravelWatson
{
    public static function handle(Throwable $exception): void
    {
        if (! config('watson.enabled')) {
            return;
        }

        Log::info('Reporting exception to Watson', ExceptionData::fromThrowable($exception)->toArray());
    }
}
