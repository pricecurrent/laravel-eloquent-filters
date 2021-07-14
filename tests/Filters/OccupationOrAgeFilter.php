<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Pricecurrent\LaravelEloquentFilters\AbstractQueryFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\ComposeableFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

class OccupationOrAgeFilter extends AbstractQueryFilter implements QueryFilterContract, ComposeableFilter
{
    protected $age;
    protected $occupation;

    public function __construct($occupation, $age)
    {
        $this->occupation = $occupation;
        $this->age = $age;
    }

    public function composedByUsingOr()
    {
        return [
            new OccupationFilter($this->occupation),
            new AgeGreaterThanFilter($this->age),
        ];
    }
}
