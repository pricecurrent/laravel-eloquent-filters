<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pricecurrent\LaravelEloquentFilters\Filterable;

class FilterableModel extends Model
{
    use Filterable;
    use HasFactory;

    /**
     * Don't protect against mass assignment.
     *
     * @var array
     */
    protected $guarded = [];
}
