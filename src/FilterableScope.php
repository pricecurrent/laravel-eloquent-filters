<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\QueryFilters;

class FilterableScope implements Scope
{
    protected $filters;

    public function __construct(QueryFilters $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        return $this->filters->apply($builder);
    }
}
