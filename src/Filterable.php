<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;

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
