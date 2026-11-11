<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\DTO;

use Laravel\Ai\Responses\StructuredAgentResponse;

class WatsonResult
{
    public function __construct(
        public string $summary,
        public string $cause,
        public string $fix,
        public string $severity,
    ) {}

    /**
     * Create a WatsonResult object from a structured agent response.
     */
    public static function fromAgentResponse(StructuredAgentResponse $data): self
    {
        return new self(
            summary: $data['summary'] ?? '',
            cause: $data['cause'] ?? '',
            fix: $data['fix'] ?? '',
            severity: $data['severity'] ?? '',
        );
    }
}
