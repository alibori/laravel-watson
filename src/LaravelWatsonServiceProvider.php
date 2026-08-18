<?php

namespace Alibori\LaravelWatson;

use Alibori\LaravelWatson\Commands\LaravelWatsonCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelWatsonServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-watson')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_watson_table')
            ->hasCommand(LaravelWatsonCommand::class);
    }
}
