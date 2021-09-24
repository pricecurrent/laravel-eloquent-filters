<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Pricecurrent\LaravelEloquentFilters\Contracts\EloquentFilterContract;
use Pricecurrent\LaravelEloquentFilters\EloquentFilters;
use Pricecurrent\LaravelEloquentFilters\Exceptions\EloquentFiltersException;

class EloquentFiltersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @covers \EloquentFilters::handle
     */
    public function it_is_buit_off_of_the_eloquent_filters()
    {
        $filterA = $this->mock(EloquentFilterContract::class);
        $filterB = $this->mock(EloquentFilterContract::class);

        $filters = EloquentFilters::make([$filterA, $filterB]);

        $this->assertCount(2, $filters);
    }

    /**
     * @test
     * @covers \EloquentFilters::handle
     */
    public function it_applies_all_the_filters()
    {
        $builder = new Builder(resolve(QueryBuilder::class));

        $filterA = $this->spy(EloquentFilterContract::class);
        $filterA->shouldReceive('isApplicable')->andReturnTrue();
        $filterA->shouldReceive('apply')->once()->with($builder);

        $filterB = $this->spy(EloquentFilterContract::class);
        $filterB->shouldReceive('isApplicable')->andReturnTrue();
        $filterB->shouldReceive('apply')->once()->with($builder);

        $filters = EloquentFilters::make([$filterA, $filterB]);

        $builder = $filters->apply($builder);
    }

    /**
     * @test
     * @covers \EloquentFilters::handle
     */
    public function it_doesnt_apply_inapplicable_filters()
    {
        $builder = new Builder(resolve(QueryBuilder::class));

        $filterA = $this->spy(EloquentFilterContract::class);
        $filterA->shouldReceive('isApplicable')->andReturnFalse();
        $filterA->shouldNotReceive('apply');

        $filterB = $this->spy(EloquentFilterContract::class);
        $filterB->shouldReceive('isApplicable')->andReturnTrue();
        $filterB->shouldReceive('apply')->once()->with($builder);

        $filters = EloquentFilters::make([$filterA, $filterB]);

        $builder = $filters->apply($builder);
    }

    /**
     * @test
     * @covers \EloquentFilters::handle
     */
    public function it_throws_an_exception_when_composed_with_non_filterable_contracts()
    {
        $builder = new Builder(resolve(QueryBuilder::class));

        $filterA = $this->spy(EloquentFilterContract::class);
        $filterB = new class() {
        };

        try {
            $filters = EloquentFilters::make([$filterA, $filterB]);
        } catch (EloquentFiltersException $e) {
            $this->assertStringContainsString('Filter must implement EloquentFilterContract', $e->getMessage());

            return;
        }

        $this->fail('Exception was not thrown when building a filters object with non-filter');
    }
}
