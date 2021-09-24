<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Support\ServiceProvider;
use Pricecurrent\LaravelEloquentFilters\Commands\FilterMakeCommand;

class EloquentFiltersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([FilterMakeCommand::class]);
    }
}
