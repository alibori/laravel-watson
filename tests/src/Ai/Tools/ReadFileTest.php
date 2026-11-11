<?php

declare(strict_types=1);

use Alibori\LaravelWatson\Ai\Tools\ReadFile;
use Illuminate\Support\Facades\File;
use Laravel\Ai\Tools\Request;

beforeEach(function () {
    $this->originalBasePath = base_path();
    $this->root = sys_get_temp_dir().'/watson-'.uniqid();

    File::makeDirectory($this->root.'/app', recursive: true);
    File::put($this->root.'/app/Crime.php', implode("\n", array_map(fn (int $i) => "line {$i}", range(1, 100))));
    File::put($this->root.'/.env', 'APP_KEY=secret');
    File::put($this->root.'/.env.production', 'APP_KEY=secret');
    File::put(dirname($this->root).'/watson-outside.php', 'outside');

    app()->setBasePath($this->root);
});

afterEach(function () {
    app()->setBasePath($this->originalBasePath);
    File::deleteDirectory($this->root);
    File::delete(dirname($this->root).'/watson-outside.php');
});

function readSource(array $arguments): string
{
    return (new ReadFile)->handle(new Request($arguments));
}

test('it reads the requested lines with line numbers', function () {
    expect(readSource(['path' => 'app/Crime.php', 'from' => 10, 'to' => 12]))
        ->toBe("  10 | line 10\n  11 | line 11\n  12 | line 12");
});

test('it reads 51 lines from the start by default', function () {
    $lines = explode("\n", readSource(['path' => 'app/Crime.php']));

    expect($lines)->toHaveCount(51)
        ->and($lines[0])->toBe('   1 | line 1')
        ->and($lines[50])->toBe('  51 | line 51');
});

test('it clamps the range to the file bounds', function () {
    $lines = explode("\n", readSource(['path' => 'app/Crime.php', 'from' => -5, 'to' => 500]));

    expect($lines)->toHaveCount(100)
        ->and($lines[0])->toBe('   1 | line 1')
        ->and($lines[99])->toBe(' 100 | line 100');
});

test('it reports missing files and directories', function (string $path) {
    expect(readSource(['path' => $path]))->toBe('File not found.');
})->with(['app/Missing.php', 'app']);

test('it refuses files outside the project', function () {
    expect(readSource(['path' => '../watson-outside.php']))->toBe('File not found.');
});

test('it refuses env files', function (string $path) {
    expect(readSource(['path' => $path]))->toBe('Access denied: this file may contain secrets.');
})->with(['.env', '.env.production']);
