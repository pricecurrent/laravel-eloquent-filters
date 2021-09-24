<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilters;

class AgeGreaterThanFilter extends AbstractEloquentFilters
{
    protected $age;

    public function __construct($age)
    {
        $this->age = $age;
    }

    public function apply(Builder $query): Builder
    {
        return $query->where('age', '>', $this->age);
    }

    public function isApplicable(): bool
    {
        return null !== $this->age && is_integer($this->age) && $this->age > 0;
    }
}
