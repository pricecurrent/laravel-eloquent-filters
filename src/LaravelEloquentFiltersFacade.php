<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pricecurrent\LaravelEloquentFilters\LaravelEloquentFilters
 */
class LaravelEloquentFiltersFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-eloquent-filters';
    }
}
