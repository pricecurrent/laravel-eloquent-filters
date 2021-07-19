<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Pricecurrent\LaravelEloquentFilters\Commands\FilterMakeCommand;
use Pricecurrent\LaravelEloquentFilters\Contracts\FilterableRequest;
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

        $this->app->afterResolving(FilterableRequest::class, function ($resolved) {
            $filters = QueryFilters::makeFromRequest($resolved);

            $definedClasses = array_filter(
                get_declared_classes(),
                function ($className) {
                    return ! call_user_func(
                        [new \ReflectionClass($className), 'isInternal']
                    );
                }
            );

            $trait = Filterable::class;

            $classes = array_filter(
                $definedClasses,
                function ($className) use ($trait) {
                    $traits = class_uses($className);

                    return isset($traits[$trait]);
                }
            );


            collect($classes)->each(fn ($class) => $class::addGlobalScope(new FilterableScope($filters)));
        });
    }
}
