<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\Contracts\Nullable;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

class IsActiveFilter implements QueryFilterContract, Nullable
{
    public function apply(Builder $query)
    {
        return $query->where('is_active', true);
    }
}
