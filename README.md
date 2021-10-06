# Advanced Laravel models filtering capabilities

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pricecurrent/laravel-eloquent-filters.svg?style=flat-square)](https://packagist.org/packages/pricecurrent/laravel-eloquent-filters)
[![run-tests](https://github.com/pricecurrent/laravel-eloquent-filters/actions/workflows/run-tests.yml/badge.svg)](https://github.com/pricecurrent/laravel-eloquent-filters/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://github.com/pricecurrent/laravel-eloquent-filters/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/pricecurrent/laravel-eloquent-filters/actions/workflows/php-cs-fixer.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/pricecurrent/laravel-eloquent-filters.svg?style=flat-square)](https://packagist.org/packages/pricecurrent/laravel-eloquent-filters)

## Installation

You can install the package via composer:

```bash
composer require pricecurrent/laravel-eloquent-filters
```

## Usage

This package gives you fine-grained control over how you may go about filtering your Eloquent Models.

This package is particularly good when you need to address complex use-cases, implementing filtering on many parameters, using complex logic.

But let's start with simple example:

Consider you have a Product, and you need to filter products by `name`:

```php
use App\Filters\NameFilter;
use Pricecurrent\LaravelEloquentFilters\EloquentFilters;

class ProductsController
{
    public function index(Request $request)
    {
        $filters = EloquentFilters::make([new NameFilter($request->name)]);

        $products = Product::filter($filters)->get();
    }
}
```
You can generate a filter with the command `php artisan eloquent-filter:make NameFilter`. This will put your Filter to the app/Filters directory by default.
You may prefix the name with the path, like `Models/Product/NameFilter`.

Here is what your `NameFilter` might look like:

```php
use Pricecurrent\LaravelEloquentFilters\AbstractEloquentFilter;
use Illuminate\Database\Eloquent\Builder;

class NameFilter extends AbstractEloquentFilter
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function apply(Builder $query): Builder
    {
        return $query->where('name', 'like', "{$this->name}%");
    }
}
```

Notice how our Filter has no clue it is tied up with a specific Eloquent Model? That means, we can simply re-use it for any other model, where we need to bring in the same name filtering functionality:

```php
use App\Filters\NameFilter;
use App\Models\User;
use Pricecurrent\LaravelEloquentFilters\EloquentFilters;

class UsersController
{
    public function index(Request $request)
    {
        $filters = EloquentFilters::make([new NameFilter($request->user_name)]);

        $products = User::filter($filters)->get();
    }
}
```

You can chain methods from the filter as if it was simply an Eloquent Builder method:

```php
use App\Filters\NameFilter;
use App\Models\User;
use Pricecurrent\LaravelEloquentFilters\EloquentFilters;

class UsersController
{
    public function index(Request $request)
    {
        $filters = EloquentFilters::make([new NameFilter($request->user_name)]);

        $products = User::query()
            ->filter($filters)
            ->limit(10)
            ->latest()
            ->get();
    }
}
```

To enable filtering capabilities on an Eloquent Model simply import the trait `Filterable`

```php

use Pricecurrent\LaravelEloquentFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable;
}
```

### More complex use-case

This approach scales very well when you are dealing with a real-life larger applications where querying data from the DB goes far beyond simple comparison by a name field.

Consider an app where we have Stores with a Location coordinates and we have products in stock and we need to query all products that are in stock in a store that is in 10 miles radius

We may stuff all the logic in the controller with some pseudo-code:

```php

class ProductsController
{
    public function index(Request $request)
    {
        $products Product::query()
            ->when($request->in_stock, function ($query) {
                $query->join('product_stock', fn ($q) => $q->on('product_stock.product_id', '=', 'products.id')->where('product_stock.quantity', '>', 0));
            })
            ->when($request->within_radius, function ($query) {
                $coordinates = auth()->user()->getCoordinates();
                $query->join('stores', 'stores.id', '=', 'product_stock.store_id');
                $query->whereRaw('
                    ST_Distance_Sphere(
                        Point(stores.longitude, stores.latitude),
                        Point(?, ?)
                    ) <= ?',
                    [$coordinates->longitude, $coordinates->latitude, $query->within_radius]
                );

            })
            ->get();

        return response()->json(['data' => $products]);
    }
}
```

This breaks Open-Closed principle and makes the code harder to test and maintain. Adding new functionality becomes a disaster.

```php
class ProductsController
{
    public function index(Request $request)
    {
        $filters = EloquentFilters::make([
            new ProductInStockFilter($request->in_stock),
            new StoreWithinDistanceFilter($request->within_radius, auth()->user()->getCoordinates())
        ]);

        $products = Product::filter($filters)->get();

        return response()->json(['data' => $products]);
    }
}
```

**Much Better!**

We can now distribute the filtering logic to a dedicated class

```php
class StoreWithinDistanceFilter extends AbstractEloquentFilter
{
    public function __construct($distance, Coordinates $fromCoordinates)
    {
        $this->distance = $distance;
        $this->fromCoordinates = $fromCoordinates;
    }

    public function apply(Builder $builder): Builder
    {
        return $builder->join('stores', 'stores.id', '=', 'product_stock.store_id')
            ->whereRaw('
                ST_Distance_Sphere(
                    Point(stores.longitude, stores.latitude),
                    Point(?, ?)
                ) <= ?',
                [$this->coordinates->longitude, $this->coordinates->latitude, $this->distance]
            );
    }
}
```

Now we have no problem with testing our functionality

```php
class StoreWithinDistanceFilterTest extends TestCase
{
    /**
     * @test
     */
    public function it_filters_products_by_store_distance()
    {
        $user = User::factory()->create(['latitude' => '...', 'longitude' => '...']);
        $store = Store::factory()->create(['latitude' => '...', 'longitude' => '...']);
        $products = Product::factory()->create();
        $store->stock()->attach($product, ['quantity' => 3]);

        $result = Product::filter(new EloquentFilters([new StoreWithinDistanceFilter(10, $user->getCoordinates())]));

        $this->assertCount(1, $result);
    }
}
```

And controller can be just tested with mocks or stubs, just making sure we have called the necessary filters.

### Checking filtering is applicable

Each filter provides method `isApplicable()` which you might implement and return boolean. If `false` is returned, the `apply` method won't be called. 

This is helpful when we don't control the incoming parameters to the filter class. In the example above we can do something like this:

```php
class StoreWithinDistanceFilter extends AbstractEloquentFilter
{
    public function __construct($distance, Coordinates $fromCoordinates)
    {
        $this->distance = $distance;
        $this->fromCoordinates = $fromCoordinates;
    }

    public function isApplicable(): bool
    {
        return $this->distance && is_numeric($this->distance);
    }

    public function apply(Builder $bulder): Builder
    {
        // your code
    }
}
```

Of course you may take another approach where you are in control of what's being passed into the filter parameters, instead of just blindly passing in the request payload.
You could leverage DTO and type-hinting for that and have Filters collection factories to properly build a collection of filters. For instance

```php
class ProductsController
{
    public function index(IndexProductsRequest $request)
    {
        $products = Product::filter(FiltersFactory::fromIndexProductsRequest($request))->get();

        return response()->json(['data' => $products]);
    }
}
```

## Testing

```bash
vendor/bin/phpunit
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
