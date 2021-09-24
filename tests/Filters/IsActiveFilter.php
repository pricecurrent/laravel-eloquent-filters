<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilters;

class IsActiveFilter extends AbstractEloquentFilters
{
    public function apply(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
