<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\Ai\Agents;

use Alibori\LaravelWatson\Ai\Tools\ReadFile;
use Alibori\LaravelWatson\Enums\Severity;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

#[MaxSteps(3)]
class LaravelWatsonAgent implements Agent, HasStructuredOutput, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
            You are an expert Laravel and PHP debugger.
            You receive the data of an exception thrown in a Laravel application:
            its class, message, file, line, stack trace and request context.
            Explain what happened, why it happened and how to fix it.
            Be concise and concrete: point to the file and line, and suggest code when it helps.
            If the data is not enough to be sure, say so instead of guessing.
            Before answering, use your tool to read the code around the failing line, and follow the stack trace if you need more context.
        PROMPT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->description('One sentence explaining what happened.')->required(),
            'cause' => $schema->string()->description('Why it happened.')->required(),
            'fix' => $schema->string()->description('How to fix it, with code if useful.')->required(),
            'severity' => $schema->string()->enum(Severity::cases())->required(),
        ];
    }

    public function tools(): array
    {
        return [
            new ReadFile,
        ];
    }
}
