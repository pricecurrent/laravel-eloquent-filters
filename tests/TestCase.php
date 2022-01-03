<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Pricecurrent\LaravelEloquentFilters\EloquentFiltersServiceProvider;
use Pricecurrent\LaravelEloquentFilters\Tests\Http\TestController;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Pricecurrent\\LaravelEloquentFilters\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        Route::get('/', [TestController::class, 'index'])->name('test');
        Route::get('/auto-apply-filters', [TestController::class, 'indexAutoApply'])->name('test-auto-apply-filters');
        Route::get('/test-inspect-query', [TestController::class, 'inspectQuery'])->name('test-inspect-query');
        Route::get('/test-manual-control-over-auto', [TestController::class, 'manualControlOverAuto'])->name('test-manual-control-over-auto');
    }

    protected function getPackageProviders($app)
    {
        return [
            EloquentFiltersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $this->createTables('filterable_models');
    }

    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function (string $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
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

    public function filtersPath($path = ''): string
    {
        return app_path('Filters' . ($path ? "/$path" : ''));
    }

    public function expectedFilesPath(string $path): string
    {
        return dirname(__FILE__) . '/expectedFiles' . ($path ? "/$path" : '');
    }
}
