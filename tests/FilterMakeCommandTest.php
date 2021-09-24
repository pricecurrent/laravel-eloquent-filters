<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class FilterMakeCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->filterName = 'DummyFilter';
        File::delete($this->filtersPath("$this->filterName.php"));
    }

    /**
     * @test
     */
    public function it_creates_a_fitler_class()
    {
        Artisan::call('eloquent-filter:make', [
            'name' => $this->filterName,
        ]);

        $this->assertTrue(File::exists($this->filtersPath("$this->filterName.php")));
    }
}
