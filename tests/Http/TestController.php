<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Http;

use Pricecurrent\LaravelEloquentFilters\QueryFilters;
use Pricecurrent\LaravelEloquentFilters\Tests\Http\TestRequest;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\LikeFilter;
use Pricecurrent\LaravelEloquentFilters\Contracts\FilterableRequest;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\IsActiveFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Models\FilterableModel;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\OccupationFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\AgeGreaterThanFilter;

class TestController
{
    public function index(TestRequest $request)
    {
        // $models = FilterableModel::filter(QueryFilters::make([
        //     (new LikeFilter($request->username))->setFieldResolver(fn () => 'name'),
        //     new AgeGreaterThanFilter($request->age),
        //     new OccupationFilter($request->occupation),
        //     new IsActiveFilter(),
        // ]))->get();
        $models = FilterableModel::filter(QueryFilters::makeFromRequest($request))->get();

        return response()->json([
            'data' => $models,
        ]);
    }

    public function indexAutoApply(TestRequest $request)
    {
        return response()->json([
            'data' => FilterableModel::all(),
        ]);
    }
}
