<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Pricecurrent\LaravelEloquentFilters\EloquentFilters;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\AgeGreaterThanFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\NameFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Models\FilterableModel;

class FilterableModelTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_filtered_with_provided_filter()
    {
        FilterableModel::factory()->create(['name' => 'john']);
        FilterableModel::factory()->create(['name' => 'jack']);
        FilterableModel::factory()->create(['name' => 'jim']);
        $filters = EloquentFilters::make([new NameFilter('jack')]);
        $model = new FilterableModel();

        $query = $model->filter($filters);
        $results = $query->get();

        $this->assertEquals(1, $results->count());
        $user = $results->pop();
        $this->assertEquals('jack', $user->name);
    }

    /**
     * @test
     */
    public function it_utilising_all_provided_filters()
    {
        $modelA = FilterableModel::factory()->create(['name' => 'john', 'age' => 20]);
        $modelB = FilterableModel::factory()->create(['name' => 'jack', 'age' => 14]);
        $modelC = FilterableModel::factory()->create(['name' => 'jim', 'age' => 23]);
        $filters = EloquentFilters::make([new NameFilter('j'), new AgeGreaterThanFilter(14)]);
        $model = new FilterableModel();

        $results = $model->filter($filters)->get();

        $this->assertEquals(2, $results->count());
        $this->assertTrue($results->contains($modelA));
        $this->assertTrue($results->contains($modelC));
    }

    /**
     * @test
     */
    public function it_is_chainable_with_other_builder_methods()
    {
        FilterableModel::factory()->create(['name' => 'john']);
        FilterableModel::factory()->create(['name' => 'jack']);
        FilterableModel::factory()->create(['name' => 'joe']);
        $filters = EloquentFilters::make([new NameFilter('jo')]);

        $results = FilterableModel::filter($filters)->orderBy('name')->get();

        $this->assertEquals(2, $results->count());
        $user = $results->shift();
        $this->assertEquals('joe', $user->name);
        $user = $results->shift();
        $this->assertEquals('john', $user->name);
    }

    /**
     * @test
     */
    public function it_ignores_filter_that_is_not_applicable()
    {
        $modelA = FilterableModel::factory()->create(['age' => 18]);
        $modelB = FilterableModel::factory()->create(['age' => 30]);

        $results = FilterableModel::filter(
            // no value provided so we skip the filtering by this criterion
            EloquentFilters::make([new AgeGreaterThanFilter(null)])
        )->get();

        $this->assertEquals(2, $results->count());
        $this->assertTrue($results->contains($modelA));
        $this->assertTrue($results->contains($modelB));
    }
}
