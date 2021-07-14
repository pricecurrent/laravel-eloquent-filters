<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Pricecurrent\LaravelEloquentFilters\Commands\LaravelEloquentFiltersCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelEloquentFiltersServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-eloquent-filters')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-eloquent-filters_table')
            ->hasCommand(LaravelEloquentFiltersCommand::class);
    }
}
