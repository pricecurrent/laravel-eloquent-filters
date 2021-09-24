<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Filters;

use Illuminate\Database\Eloquent\Builder;
use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilter;

class OccupationFilter extends AbstractEloquentFilter
{
    protected $occupation;

    public function __construct($occupation)
    {
        $this->occupation = $occupation;
    }

    public function apply(Builder $query): Builder
    {
        return $query->where('occupation', '=', $this->occupation);
    }

    public function isApplicable(): bool
    {
        return $this->occupation !== null;
    }
}
