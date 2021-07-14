<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use LogicException;
use Pricecurrent\LaravelEloquentFilters\Contracts\ComposeableFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

class QueryFilters extends Collection
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
        return tap($builder, function ($builder) {
            $this->each(function ($filter) use ($builder) {
                if ($filter instanceof ComposeableFilter) {
                    return $builder->where(function ($query) use ($filter) {
                        collect($filter->composedByUsingOr())->each(function ($filter) use ($query) {
                            return $filter->setUsingOr(true)->apply($query);
                        });
                    });
                }

                return $filter->apply($builder);
            });
        });
    }

    protected static function validateParamters($items)
    {
        collect($items)
            ->each(
                fn ($item) => throw_unless(
                    $item instanceof QueryFilterContract,
                    new LogicException('Filter must implement QueryFilterContract')
                )
            );
    }
}
