<?php

namespace App\Filters;

use App\Filters;
use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilter;

class DummyFilter extends AbstractEloquentFilter
{
    protected $name;

    public function __constructor($name)
    {
        $this->name;
    }

    public function apply(Builder $query): Builder
    {
        return $query->where('name', 'like', "$this->name%");
    }
}
