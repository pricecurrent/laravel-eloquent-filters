<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

class NameFilter implements QueryFilterContract
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function apply(Builder $query)
    {
        return $query->where('name', 'like', "%{$this->name}%");
    }
}
