<?php

namespace Pricecurrent\LaravelEloquentFilters\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class FilterMakeCommand extends GeneratorCommand
{
    protected $signature = 'make:eloquent-filter {name} {--field}';

    protected $description = 'Create a Eloquent Filter Class';

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
        if ($this->option('field')) {
            return dirname(__DIR__, 1) . '/stubs/filter.field.stub';
        }

        return dirname(__DIR__, 1) . '/stubs/filter.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Filters';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name' => InputOption::VALUE_REQUIRED, 'Name of the Filter that should be created'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['field' => InputOption::VALUE_OPTIONAL, 'The name of the Filter field']
        ];
    }


    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
            ->replaceField($stub, $this->option('field'))
            ->replaceClass($stub, $name);
    }

    public function replaceField(string &$stub, ?string $field): self
    {
        if ($field) {
            $stub = str_replace('{{ field }}', $field, $stub);
        }

        return $this;
    }
}
