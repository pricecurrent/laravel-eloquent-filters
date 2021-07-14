<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Pricecurrent\LaravelEloquentFilters\AbstractQueryFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

class OccupationFilter extends AbstractQueryFilter implements QueryFilterContract
{
    protected $occupation;

    public function __construct($occupation)
    {
        $this->occupation = $occupation;
    }

    public function field()
    {
        return 'occupation';
    }

    public function operator()
    {
        return '=';
    }

    public function value()
    {
        return $this->occupation;
    }
}
