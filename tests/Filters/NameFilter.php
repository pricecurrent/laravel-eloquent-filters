<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilters;

class NameFilter extends AbstractEloquentFilters
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
