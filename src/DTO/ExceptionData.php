<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\DTO;

use Throwable;

class ExceptionData
{
    public function __construct(
        public string $class,
        public string $message,
        public string $file,
        public int $line,
        public string $trace,
        public string $occurredAt,
        public ?string $url = null,
        public ?string $method = null,
        public int|string|null $userId = null,
    ) {}

    /**
     * Create an ExceptionData object from a Throwable.
     */
    public static function fromThrowable(Throwable $exception): self
    {
        $request = app()->runningInConsole() ? null : request();

        return new self(
            class: $exception::class,
            message: $exception->getMessage(),
            file: $exception->getFile(),
            line: $exception->getLine(),
            trace: $exception->getTraceAsString(),
            occurredAt: now()->toDateTimeString(),
            url: $request?->fullUrl(),
            method: $request?->method(),
            userId: $request?->user()?->getAuthIdentifier(),
        );
    }

    /**
     * Convert the ExceptionData object to an array.
     *
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
