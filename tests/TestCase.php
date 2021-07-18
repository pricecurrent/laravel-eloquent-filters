<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Pricecurrent\LaravelEloquentFilters\QueryFiltersServiceProvider;
use Pricecurrent\LaravelEloquentFilters\Tests\Http\TestController;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Pricecurrent\\LaravelEloquentFilters\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Route::get('/', [TestController::class, 'index'])->name('test');
        Route::get('/auto-apply-filters', [TestController::class, 'indexAutoApply'])->name('test-auto-apply-filters');
        Builder::macro('autoApplyFilters', function ($query) {
            dd($query);
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            QueryFiltersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        $this->createTables('filterable_models');
    }

    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function (string $tableName) {
            Schema::create($tableName, function (Blueprint $table) use ($tableName) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('text')->nullable();
                $table->string('occupation')->nullable();
                $table->integer('age')->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
                $table->softDeletes();
            });
        });
    }

    public function filtersPath($path = '')
    {
        return app_path('Filters' . ($path ? "/$path" : ''));
    }
}
