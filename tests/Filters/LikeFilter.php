<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilter;

class LikeFilter extends AbstractEloquentFilter
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function apply(Builder $query): Builder
    {
        return $query->where($this->field(), 'like', "%$this->value%");
    }
}
