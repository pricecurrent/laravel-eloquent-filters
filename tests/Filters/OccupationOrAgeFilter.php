<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilters;
use Pricecurrent\LaravelEloquentFilters\Contracts\ComposeableFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\EloquentFilterContract;

class OccupationOrAgeFilter extends AbstractEloquentFilters implements EloquentFilterContract, ComposeableFilter
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
