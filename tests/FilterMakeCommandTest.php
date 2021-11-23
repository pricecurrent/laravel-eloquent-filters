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
        Artisan::call('make:eloquent-filter', [
            'name' => $this->filterName,
        ]);

        $expectedFile = $this->expectedFilesPath('FilterMakerCommand/it_creates_a_filter_class.php');
        $resultFile = $this->filtersPath("$this->filterName.php");

        $this->assertFileEquals($expectedFile, $resultFile);
    }

    /**
     * @test
     */
    public function it_creates_a_filter_class_with_field_name()
    {
        Artisan::call('make:eloquent-filter', [
            'name' => $this->filterName,
            '--field' => 'name',
        ]);
        $expectedFile = $this->expectedFilesPath('FilterMakerCommand/it_creates_a_filter_class_with_field_name.php');
        $resultFile = $this->filtersPath("$this->filterName.php");

        $this->assertFileEquals($expectedFile, $resultFile);
    }
}
