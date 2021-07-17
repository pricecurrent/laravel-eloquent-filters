<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractQueryFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\FieldAgnostic;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

class LikeFilter extends AbstractQueryFilter implements QueryFilterContract, FieldAgnostic
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function apply(Builder $builder)
    {
        return $builder->where($this->field(), 'like', "%$this->value%");
    }
}
