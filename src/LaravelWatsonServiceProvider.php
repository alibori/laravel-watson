<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelWatsonServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('watson')
            ->hasConfigFile();
    }
}
