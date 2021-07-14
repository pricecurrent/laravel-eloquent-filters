<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;
use Pricecurrent\LaravelEloquentFilters\QueryFilters;

class QueryFiltersTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_instantiated_with_filters()
    {
        $filterA = $this->mock(QueryFilterContract::class);
        $filterB = $this->mock(QueryFilterContract::class);

        $filters = QueryFilters::make([$filterA, $filterB]);
        $this->assertEquals(2, $filters->count());
    }

    /**
     * @test
     */
    public function it_can_not_instantiate_with_invalid_filters()
    {
        $filterA = 'not-a-filter';

        try {
            $filters = QueryFilters::make([$filterA]);
        } catch (\LogicException $e) {
            $this->assertTrue(true);

            return;
        }

        $this->fail('QueryFilters was instantiated with invalid filter parameters.');
    }

    /**
     * @test
     */
    public function it_can_not_instantiate_with_invalid_filters_via_constructor()
    {
        $filterA = 'not-a-filter';

        try {
            $filters = new QueryFilters([$filterA]);
        } catch (\LogicException $e) {
            $this->assertTrue(true);

            return;
        }

        $this->fail('QueryFilters was instantiated with invalid filter parameters.');
    }

    /**
     * @test
     */
    public function it_applies_all_filters()
    {
        $filterA = $this->spy(QueryFilterContract::class);
        $filterB = $this->spy(QueryFilterContract::class);
        $filters = QueryFilters::make([$filterA, $filterB]);

        $filters->apply($builder = new Builder(resolve(\Illuminate\Database\Query\Builder::class)));

        $filterA->shouldHaveReceived('apply')->once()->with($builder);
        $filterB->shouldHaveReceived('apply')->once()->with($builder);
    }
}
