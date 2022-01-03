<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilter;

class DummyFilter extends AbstractEloquentFilter
{
    public function apply(Builder $query)
    {
        // your code
    }
}
