<?php

namespace Pricecurrent\LaravelEloquentFilters\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface QueryFilterContract
{
    public function apply(Builder $builder);
}
