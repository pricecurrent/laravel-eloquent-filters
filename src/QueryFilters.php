<?php

namespace Pricecurrent\LaravelEloquentFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LogicException;
use Pricecurrent\LaravelEloquentFilters\Contracts\ComposeableFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\FieldAgnostic;
use Pricecurrent\LaravelEloquentFilters\Contracts\Nullable;
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
            ->map(function ($filter, $field) use ($request) {
                list($filter, $dbField) = static::qualifyFilter($filter, $field);

                return tap(new $filter($request->get($field)), function ($filter) use ($dbField) {
                    $filter->setFieldResolver($dbField);

                    return $filter;
                });
            });

        return static::make($filters);
    }

    public function apply(Builder $builder)
    {
        return tap($builder, function ($builder) {
            $this
                ->filter(function ($filter) {
                    return $filter->isApplicable();
                })
                ->each(function ($filter) use ($builder) {
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
