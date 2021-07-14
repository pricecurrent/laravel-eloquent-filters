<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Pricecurrent\LaravelEloquentFilters\LaravelEloquentFiltersServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Pricecurrent\\LaravelEloquentFilters\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelEloquentFiltersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        include_once __DIR__.'/../database/migrations/create_laravel-eloquent-filters_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
