<?php

namespace Pricecurrent\LaravelEloquentFilters\Commands;

use Illuminate\Console\GeneratorCommand;

class FilterMakeCommand extends GeneratorCommand
{
    protected $signature = 'eloquent-filter:make {name}';

    protected $description = 'Create a Query Filter Class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Filter';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return dirname(__DIR__, 1) . '/stubs/filter.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Filters';
    }
}
