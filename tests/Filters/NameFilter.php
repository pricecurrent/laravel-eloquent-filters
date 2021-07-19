<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractQueryFilter;

class NameFilter extends AbstractQueryFilter
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function apply(Builder $query): Builder
    {
        return $query->where('name', 'like', "%{$this->name}%");
    }
}
