<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Http;

use Illuminate\Foundation\Http\FormRequest;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\NameFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\IsActiveFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\OccupationFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\AgeGreaterThanFilter;

class TestRequest extends FormRequest
{
    public function filters()
    {
        return [
            'name' => NameFilter::class,
            'age' => AgeGreaterThanFilter::class,
            'occupation' => OccupationFilter::class,
            'active' => IsActiveFilter::class,
        ];
    }

    public function rules()
    {
        return [];
    }
}
