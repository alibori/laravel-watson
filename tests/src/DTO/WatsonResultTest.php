<?php

declare(strict_types=1);

use Alibori\LaravelWatson\DTO\WatsonResult;
use Laravel\Ai\Responses\Data\Meta;
use Laravel\Ai\Responses\Data\Usage;
use Laravel\Ai\Responses\StructuredAgentResponse;

function structuredResponse(array $structured): StructuredAgentResponse
{
    return new StructuredAgentResponse('invocation-id', $structured, json_encode($structured), new Usage, new Meta);
}

test('watson result is built from the agent response', function () {
    $result = WatsonResult::fromAgentResponse(structuredResponse([
        'summary' => 'Null user',
        'cause' => 'Guest request',
        'fix' => 'Add auth middleware',
        'severity' => 'high',
    ]));

    expect($result)
        ->summary->toBe('Null user')
        ->cause->toBe('Guest request')
        ->fix->toBe('Add auth middleware')
        ->severity->toBe('high');
});

test('watson result defaults missing fields to empty strings', function () {
    $result = WatsonResult::fromAgentResponse(structuredResponse([]));

    expect($result)
        ->summary->toBe('')
        ->cause->toBe('')
        ->fix->toBe('')
        ->severity->toBe('');
});
