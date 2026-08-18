<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Alibori\LaravelWatson\LaravelWatson
 */
class LaravelWatson extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Alibori\LaravelWatson\LaravelWatson::class;
    }
}
