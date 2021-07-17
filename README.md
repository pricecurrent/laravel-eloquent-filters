# Advanced Laravel models filtering capabilities

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pricecurrent/laravel-eloquent-filters.svg?style=flat-square)](https://packagist.org/packages/pricecurrent/laravel-eloquent-filters)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/pricecurrent/laravel-eloquent-filters/run-tests?label=tests)](https://github.com/pricecurrent/laravel-eloquent-filters/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/pricecurrent/laravel-eloquent-filters/Check%20&%20fix%20styling?label=code%20style)](https://github.com/pricecurrent/laravel-eloquent-filters/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/pricecurrent/laravel-eloquent-filters.svg?style=flat-square)](https://packagist.org/packages/pricecurrent/laravel-eloquent-filters)

## Installation

You can install the package via composer:

```bash
composer require pricecurrent/laravel-eloquent-filters
```

You can publish and run the migrations with:

## Usage

This package gives you fine-grained control over how you may go about filtering your Eloquent Models.

This package is particularly good when you need to address complex use-cases, implementing filtering on many parameters, using complex logic.

But let's start with simple example:

Consider you have a Product, and you need to filter products by `name`:

```php
use App\Models\Filters\NameFilter;
// @todo:: import facade path

class ProductsController
{
    public function index(Request $request)
    {
        $filters = Filters::make([new NameFilter($request->name)]);

        $products = Product::filter($filters)->get();
    }
}
```

Here is what your `NameFilter` might look like:

```php
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;
use Pricecurrent\LaravelEloquentFilters\AbstractQueryFilter;

// todo:: rename Abstract Query Filter
class NameFilter extends AbstractQueryFilter implements QueryFilterContract
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function field()
    {
        return 'name';
    }

    public function operator()
    {
        return 'like';
    }

    public function value()
    {
        return "%{$this->name}%";
    }
}
```

Notice how our Filter has no clue it tied up with a specific Eloquent Model? That means, we can simply re-use it for any other model, where we need to bring in the same name filtering functionality:

```php
use App\Models\Filters\NameFilter;
use App\Models\User;
// @todo:: import facade path

class UsersController
{
    public function index(Request $request)
    {
        $filters = Filters::make([new NameFilter($request->user_name)]);

        $products = User::filter($filters)->get();
    }
}
```

You can chain methods from the filter as if it was a simple Eloquent Builder method:

```php
use App\Models\Filters\NameFilter;
use App\Models\User;
// @todo:: import facade path

class UsersController
{
    public function index(Request $request)
    {
        $filters = Filters::make([new NameFilter($request->user_name)]);

        $products = User::query()
            ->filter($filters)
            ->limit(10)
            ->latest()
            ->get();
    }
}
```

To enable filtering capabilities to an Eloquent Model simply import a trait

```php

use Pricecurrent\LaravelEloquentFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable;
}
```

### Fine-grained control

If you want to have a complete control over how you implement your query logic, you can implement `apply` method on a filter class directly

```php
use Pricecurrent\LaravelEloquentFilters\Contracts\QueryFilterContract;

class NameFilter implements QueryFilterContract
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function apply(Builder $builder)
    {
        return $builder->where('name', 'like', "%{$this->name}%");
    }
}
```

> Notice how you don't have to extends `\Pricecurrent\LaravelEloquentFilters\AbstractQueryFilter` class anymore as you are implementing `apply` method manually


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Andrew Malinnikov](https://github.com/pricecurrent)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
