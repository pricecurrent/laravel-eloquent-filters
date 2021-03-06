<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Pricecurrent\LaravelEloquentFilters\EloquentFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, EloquentFilters $filters): Builder
    {
        return $filters->apply($query);
    }
}
