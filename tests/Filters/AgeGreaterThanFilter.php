<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Pricecurrent\LaravelEloquentFilters\AbstractQueryFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

class AgeGreaterThanFilter extends AbstractQueryFilter implements QueryFilterContract
{
    protected $age;

    public function __construct($age)
    {
        $this->age = $age;
    }

    public function field()
    {
        return 'age';
    }

    public function operator()
    {
        return '>';
    }

    public function value()
    {
        return $this->age;
    }
}
