<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Pricecurrent\LaravelEloquentFilters\Commands\FilterMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class QueryFiltersServiceProvider extends PackageServiceProvider
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
            ->hasCommand(FilterMakeCommand::class);
    }
}
