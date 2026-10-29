<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson;

use Alibori\LaravelWatson\Ai\Agents\LaravelWatsonAgent;
use Alibori\LaravelWatson\DTO\ExceptionData;
use Illuminate\Support\Facades\Log;
use Throwable;

class LaravelWatson
{
    private static bool $isHandlingException = false;

    public static function handle(Throwable $exception): void
    {
        if (! config('watson.enabled') || self::$isHandlingException) {
            return;
        }

        self::$isHandlingException = true;

        try {
            $data = ExceptionData::fromThrowable($exception)->toArray();

            $response = LaravelWatsonAgent::make()->prompt(
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
            );

            dd($response);
        } catch (Throwable $e) {
            Log::error('Error handling exception with Laravel Watson: '.$e->getMessage(), [
                'original_exception' => $exception,
                'handling_exception' => $e,
            ]);
        } finally {
            self::$isHandlingException = false;
        }
    }
}
