<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Http;

use Pricecurrent\LaravelEloquentFilters\QueryFilters;
use Pricecurrent\LaravelEloquentFilters\Tests\Models\FilterableModel;

class TestController
{
    public function index(TestRequest $request)
    {
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
