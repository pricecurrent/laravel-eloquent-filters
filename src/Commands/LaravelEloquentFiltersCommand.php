<?php

namespace Pricecurrent\LaravelEloquentFilters\Commands;

use Illuminate\Console\Command;

class LaravelEloquentFiltersCommand extends Command
{
    public $signature = 'laravel-eloquent-filters';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
