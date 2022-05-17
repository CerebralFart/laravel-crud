<?php

namespace Cerebralfart\LaravelCRUD\Test\Actions;

use Cerebralfart\LaravelCRUD\Test\Mocks\Pokemon;
use Cerebralfart\LaravelCRUD\Test\TestCase;
use Illuminate\Testing\Assert;

class IndexActionTest extends TestCase {
    public function test_default_list() {
        $response = $this->get('/pokemon/')->json('data');

        $this->assertDatabaseCount('pokemon', count($response));
        foreach ($response as $pokemon) {
            $this->assertEquals(
                Pokemon::find($pokemon['id'])->getAttributes(),
                $pokemon
            );
        }
    }

    public function test_filter() {
        $response = $this->get('/pokemon/?_filter[]=small')->json('data');

        $this->assertCount(7, $response);
    }

    public function test_filter_inversion() {
        $response = $this->get('/pokemon/?_filter[]=!small')->json('data');

        $this->assertCount(8, $response);
    }

    public function test_filter_compounding() {
        $response = $this->get('/pokemon/?_filter[]=small&_filter[]=!light')->json('data');

        $this->assertCount(1, $response);
        Assert::assertArraySubset([
            ['name' => 'Kakuna']
        ], $response);
    }

    public function test_search() {
        $response = $this->get('/pokemon/?_search=Char')->json('data');

        $this->assertCount(3, $response);
        Assert::assertArraySubset([
            ['name' => 'Charmander'],
            ['name' => 'Charmeleon'],
            ['name' => 'Charizard']
        ], $response);
    }

    public function test_order() {
        $response = $this->get('/pokemon/?_order=weight')->json('data');

        $this->assertCount(15, $response);
        Assert::assertArraySubset([
            ['weight' => 2.9],
            ['weight' => 3.2],
            ['weight' => 6.9],
            ['weight' => 8.5],
            ['weight' => 9.0],
            ['weight' => 9.9],
            ['weight' => 10.0],
            ['weight' => 13.0],
            ['weight' => 19.0],
            ['weight' => 22.5],
            ['weight' => 29.5],
            ['weight' => 32.0],
            ['weight' => 85.5],
            ['weight' => 90.5],
            ['weight' => 100.0],
        ], $response);
    }

    public function test_order_direction() {
        $response = $this->get('/pokemon/?_order=weight&_direction=DESC')->json('data');

        $this->assertCount(15, $response);
        Assert::assertArraySubset([
            ['weight' => 100.0],
            ['weight' => 90.5],
            ['weight' => 85.5],
            ['weight' => 32.0],
            ['weight' => 29.5],
            ['weight' => 22.5],
            ['weight' => 19.0],
            ['weight' => 13.0],
            ['weight' => 10.0],
            ['weight' => 9.9],
            ['weight' => 9.0],
            ['weight' => 8.5],
            ['weight' => 6.9],
            ['weight' => 3.2],
            ['weight' => 2.9],
        ], $response);
    }
}
