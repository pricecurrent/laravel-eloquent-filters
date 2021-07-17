<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractQueryFilter
{
    protected $usingOr = false;
    protected $fieldResolver;

    public function apply(Builder $builder)
    {
        $method = $this->getSelectLogic();

        return $builder->$method($this->field(), $this->operator(), $this->value());
    }

    public function field()
    {
        if (! $this->fieldResolver) {
            return;
        }

        if (is_callable($this->fieldResolver)) {
            $callable = $this->fieldResolver;
            return $callable();
        }

        return $this->fieldResolver;
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

    public function setFieldResolver($resolver)
    {
        $this->fieldResolver = $resolver;
        return $this;
    }
}
