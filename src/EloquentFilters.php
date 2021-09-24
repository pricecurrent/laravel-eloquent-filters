<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Pricecurrent\LaravelEloquentFilters\Contracts\EloquentFilterContract;
use Pricecurrent\LaravelEloquentFilters\Exceptions\EloquentFiltersException;

class EloquentFilters extends Collection
{
    public function __construct($items = [])
    {
        static::validateParamters($items);

        return parent::__construct($items);
    }

    /**
     * @param  mixed  $items
     * @return static
     */
    public static function make($items = [])
    {
        static::validateParamters($items);

        return parent::make($items);
    }

    public function apply(Builder $builder)
    {
        $this
            ->removeNotApplicable()
            ->each(fn ($filter) => $filter->apply($builder));

        return $builder;
    }

    public function removeNotApplicable()
    {
        return $this->filter->isApplicable();
    }

    protected static function validateParamters($items)
    {
        collect($items)->each(
            fn ($item) => throw_unless(
                $item instanceof EloquentFilterContract,
                new EloquentFiltersException("Filter must implement EloquentFilterContract")
            )
        );
    }
}
