<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Pricecurrent\LaravelEloquentFilters\QueryFilters;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\AgeGreaterThanFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\LikeFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\NameFilter;
use Pricecurrent\LaravelEloquentFilters\Tests\Filters\OccupationOrAgeFilter;
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
        $filters = QueryFilters::make([new NameFilter('jack')]);
        $model = new FilterableModel();

        $results = $model->filter($filters)->get();

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
        $filters = QueryFilters::make([new NameFilter('j'), new AgeGreaterThanFilter(14)]);
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
        $filters = QueryFilters::make([new NameFilter('jo')]);

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
    public function it_is_filtered_with_composable_filter()
    {
        $modelA = FilterableModel::factory()->create([
            'occupation' => 'php dev',
            'age' => 15,
        ]);
        $modelB = FilterableModel::factory()->create([
            'occupation' => 'student',
            'age' => 19,
        ]);
        $modelC = FilterableModel::factory()->create([
            'occupation' => 'php dev',
            'age' => 32,
        ]);
        $modelD = FilterableModel::factory()->create([
            'occupation' => 'student',
            'age' => 18,
        ]);
        $filter = new OccupationOrAgeFilter('php dev', 18);

        $query = FilterableModel::filter(new QueryFilters([$filter]));
        $results = $query->get();

        $this->assertEquals(3, $results->count());
        $this->assertTrue($results->contains($modelA));
        $this->assertTrue($results->contains($modelB));
        $this->assertTrue($results->contains($modelC));
    }

    /**
     * @test
     */
    public function it_filters_by_field_agnostic_filter()
    {
        $filter = (new LikeFilter('Jan'))->setFieldResolver('name');

        $modelA = FilterableModel::factory()->create(['name' => 'January']);
        $modelB = FilterableModel::factory()->create(['name' => 'February']);

        $results = FilterableModel::filter(QueryFilters::make([$filter]))->get();

        $this->assertEquals(1, $results->count());
        $this->assertTrue($results->contains($modelA));

        // using same filter to filter by another field
        $modelA = FilterableModel::factory()->create(['text' => 'january is a first month']);
        $modelB = FilterableModel::factory()->create(['text' => 'nothing about that keyword']);
        $filter->setFieldResolver(fn () => 'text');

        $results = FilterableModel::filter(QueryFilters::make([$filter]))->get();

        $this->assertEquals(1, $results->count());
        $this->assertTrue($results->contains($modelA));
    }
}
