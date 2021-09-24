<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\Contracts\EloquentFilterContract;

abstract class AbstractEloquentFilter implements EloquentFilterContract
{
    abstract public function apply(Builder $query): Builder;

    public function isApplicable(): bool
    {
        return true;
    }
}
