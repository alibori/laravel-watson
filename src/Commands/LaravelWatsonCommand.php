<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\Commands;

use Illuminate\Console\Command;

class LaravelWatsonCommand extends Command
{
    public $signature = 'laravel-watson';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
