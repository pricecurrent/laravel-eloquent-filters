<?php

namespace Pricecurrent\LaravelEloquentFilters\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Pricecurrent\LaravelEloquentFilters\Tests\Models\FilterableModel;

class FilterableModelFactory extends Factory
{
    protected $model = FilterableModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'text' => $this->faker->text,
        ];
    }
}

