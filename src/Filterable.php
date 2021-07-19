<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\QueryFilters;
use Pricecurrent\LaravelEloquentFilters\FilterableScope;
use Pricecurrent\LaravelEloquentFilters\Contracts\FilterableRequest;
use Pricecurrent\LaravelEloquentFilters\Exceptions\EloquentFiltersException;

trait Filterable
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Pricecurrent\LaravelEloquentFilters\QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}
