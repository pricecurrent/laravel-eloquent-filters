<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractQueryFilter
{
    protected $usingOr = false;

    public function apply(Builder $builder)
    {
        $method = $this->getSelectLogic();

        return $builder->$method($this->field(), $this->operator(), $this->value());
    }

    public function operator()
    {
        return '=';
    }

    public function setUsingOr(bool $value)
    {
        $this->usingOr = $value;

        return $this;
    }

    protected function getSelectLogic()
    {
        return $this->usingOr === true ? 'orWhere' : 'where';
    }
}
