<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final class ReadFile implements Tool
{
    public function description(): string
    {
        return 'Read lines from a source file of the Laravel application. Use it to inspect the code where the exception happened and the files in the stack trace.';
    }

    public function handle(Request $request): string
    {
        $path = realpath(base_path($request['path']));

        if ($path === false || ! is_file($path) || ! str_starts_with($path, base_path().DIRECTORY_SEPARATOR)) {
            return 'File not found.';
        }

        if (str_starts_with(basename($path), '.env')) {
            return 'Access denied: this file may contain secrets.';
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);

        if ($lines === false) {
            return 'File could not be read.';
        }

        $from = max((int) ($request['from'] ?? 1), 1);
        $to = min((int) ($request['to'] ?? $from + 50), count($lines));

        return collect(array_slice($lines, $from - 1, $to - $from + 1, true))
            ->map(fn (string $code, int $i): string => sprintf('%4d | %s', $i + 1, $code))
            ->implode("\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'path' => $schema->string()->description('Path relative to the project root, e.g. routes/web.php')->required(),
            'from' => $schema->integer()->description('First line to read. Defaults to 1.'),
            'to' => $schema->integer()->description('Last line to read. Defaults to from + 50.'),
        ];
    }
}
