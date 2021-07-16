<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Pricecurrent\LaravelEloquentFilters\Tests\TestCase;
use Pricecurrent\LaravelEloquentFilters\Tests\Models\FilterableModel;

class EndpointTest extends TestCase
{
    /** @test */
    public function it_applies_mapped_filters_to_request_fields_and_ignores_the_ones_where_we_dont_have_value_for()
    {
        $john = FilterableModel::factory()->create([
            'name' => 'John', 'age' => 38, 'occupation' => 'php dev',
        ]);
        $jane = FilterableModel::factory()->create([
            'name' => 'Jane', 'age' => 18, 'occupation' => 'student',
        ]);
        $bradley = FilterableModel::factory()->create([
            'name' => 'Bradley', 'age' => 22, 'occupation' => null,
        ]);
        $joey = FilterableModel::factory()->create([
            'name' => 'Joey', 'age' => 23, 'occupation' => 'alcoholic',
        ]);

        $response = $this->json('get', route('test'), [
            'name' => 'J'
        ]);

        $response->assertOk();
        $this->assertCount(3, $response->json('data'));
        $results = collect($response->json('data'));
        $this->assertTrue($results->contains(fn ($i) => $i['name'] == 'John'));
        $this->assertTrue($results->contains(fn ($i) => $i['name'] == 'Jane'));
        $this->assertTrue($results->contains(fn ($i) => $i['name'] == 'Joey'));
    }

    /** @test */
    public function it_applies_all_mapped_filters_to_request_parameters()
    {
        $john = FilterableModel::factory()->create([
            'name' => 'John', 'age' => 38, 'occupation' => 'php dev',
        ]);
        $jane = FilterableModel::factory()->create([
            'name' => 'Jane', 'age' => 18, 'occupation' => 'student',
        ]);
        $bradley = FilterableModel::factory()->create([
            'name' => 'Bradley', 'age' => 22, 'occupation' => null,
        ]);
        $joey = FilterableModel::factory()->create([
            'name' => 'Joey', 'age' => 23, 'occupation' => 'alcoholic',
        ]);

        $response = $this->json('get', route('test'), [
            'name' => 'J',
            'age' => 20,
            'occupation' => 'php dev',
        ]);

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $results = collect($response->json('data'));
        $this->assertTrue($results->contains(fn ($i) => $i['name'] == 'John'));
    }

    /** @test */
    public function it_applies_nullable_filters_to_a_field_where_value_is_not_expected()
    {
        $john = FilterableModel::factory()->create([
            'name' => 'John', 'age' => 38, 'occupation' => 'php dev', 'is_active' => false,
        ]);
        $jane = FilterableModel::factory()->create([
            'name' => 'Jane', 'age' => 18, 'occupation' => 'student', 'is_active' => true,
        ]);
        $bradley = FilterableModel::factory()->create([
            'name' => 'Bradley', 'age' => 22, 'occupation' => null, 'is_active' => true,
        ]);
        $joey = FilterableModel::factory()->create([
            'name' => 'Joey', 'age' => 23, 'occupation' => 'alcoholic', 'is_active' => false,
        ]);

        // is supposed to filter by is_active = true field in the DB,
        // and the ones who is older than 18.
        // but the API design is to NOT pass any value along with it
        $response = $this->json('get', route('test'), [
            'age' => 18,
            'active',
        ]);

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $results = collect($response->json('data'));
        $this->assertTrue($results->contains(fn ($i) => $i['name'] == 'Bradley'));
    }
}
