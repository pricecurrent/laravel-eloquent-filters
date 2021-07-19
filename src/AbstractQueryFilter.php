<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

abstract class AbstractQueryFilter implements QueryFilterContract
{
    protected $fieldResolver;

    abstract public function apply(Builder $query): Builder;

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

    public function setFieldResolver($resolver)
    {
        $this->fieldResolver = $resolver;

        return $this;
    }

    public function isApplicable(): bool
    {
        return true;
    }
}
