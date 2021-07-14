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
    public function it_creates_a_filter_class()
    {
        Artisan::call('query-filters:make', ['name' => $this->filterName]);

        $this->assertTrue(File::exists($this->filtersPath("$this->filterName.php")));
    }

    /**
     * @test
     */
    public function it_creates_a_composeable_filter_class()
    {
        Artisan::call('query-filters:make', [
            'name' => $this->filterName,
            '--composeable' => true,
        ]);

        $this->assertTrue(File::exists($this->filtersPath("$this->filterName.php")));
    }

    /**
     * @test
     */
    public function it_creates_a_raw_fitler_class()
    {
        Artisan::call('query-filters:make', [
            'name' => $this->filterName,
            '--raw' => true,
        ]);

        $this->assertTrue(File::exists($this->filtersPath("$this->filterName.php")));
    }
}
