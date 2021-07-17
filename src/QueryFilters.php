<?php

namespace Pricecurrent\LaravelEloquentFilters;

use LogicException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\Contracts\Nullable;
use Pricecurrent\LaravelEloquentFilters\Contracts\FieldAgnostic;
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

    protected static function qualifyFilter($filter, $field)
    {
        if (is_string($filter)) {
            return [$filter, $field];
        }

        if (is_array($filter)) {
            return [$filter['name'], $filter['field']];
        }
    }

    public static function makeFromRequest(Request $request)
    {
        $filters = collect($request->filters())
            ->filter(function ($filter, $field) use ($request) {
                list($filter, $dbField) = static::qualifyFilter($filter, $field);
                if (static::filterDoesntNeedValue($filter)) {
                    return true;
                }

                return ! ! $request->get($field) === true;
            })
            ->map(function ($filter, $field) use ($request) {
                list($filter, $dbField) = static::qualifyFilter($filter, $field);
                return tap(new $filter($request->get($field)), function ($filter) use ($dbField) {
                    if ($filter instanceof FieldAgnostic) {
                        $filter->setFieldResolver($dbField);
                    }
                    return $filter;
                });
            });

        return static::make($filters);
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

    protected static function filterDoesntNeedValue($filter)
    {
        $reflect = new \ReflectionClass($filter);

        return collect($reflect->getInterfaces())
            ->contains(fn ($interface) => $interface->name == Nullable::class);
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
