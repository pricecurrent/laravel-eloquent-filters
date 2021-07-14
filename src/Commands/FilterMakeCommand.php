<?php

namespace Pricecurrent\LaravelEloquentFilters\Commands;

use Illuminate\Console\GeneratorCommand;

class FilterMakeCommand extends GeneratorCommand
{
    protected $signature = 'query-filters:make {name} {--composeable} {--raw}';

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
        if ($this->option('raw')) {
            return dirname(__DIR__, 1).'/stubs/filter.raw.stub';
        }

        return $this->option('composeable')
            ? dirname(__DIR__, 1).'/stubs/filter.composeable.stub'
            : dirname(__DIR__, 1).'/stubs/filter.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Filters';
    }
}
