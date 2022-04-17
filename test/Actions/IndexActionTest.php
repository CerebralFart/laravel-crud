<?php

namespace Cerebralfart\LaravelCRUD\Test\Actions;

use Cerebralfart\LaravelCRUD\Test\Mocks\Pokemon;
use Cerebralfart\LaravelCRUD\Test\TestCase;

class IndexActionTest extends TestCase {
    public function test_default_list() {
        $response = $this->get('/pokemon/');
        $json = json_decode($response->content(), TRUE);

        $this->assertDatabaseCount('pokemon', count($json));
        foreach ($json as $pokemon) {
            $this->assertEquals(
                Pokemon::find($pokemon['id'])->getAttributes(),
                $pokemon
            );
        }
    }

    public function test_filter() {
        $response = $this->get('/pokemon/?_filter[]=small');

        $response->assertJsonCount(7);
    }

    public function test_filter_inversion() {
        $response = $this->get('/pokemon/?_filter[]=!small');

        $response->assertJsonCount(8);
    }

    public function test_filter_compounding() {
        $response = $this->get('/pokemon/?_filter[]=small&_filter[]=!light');

        $response->assertJsonCount(1);
        $response->assertJson([
            ['name' => 'Kakuna']
        ]);
    }

    public function test_search() {
        $response = $this->get('/pokemon/?_search=Char');

        $response->assertJsonCount(3);
        $response->assertJson([
            ['name' => 'Charmander'],
            ['name' => 'Charmeleon'],
            ['name' => 'Charizard']
        ]);
    }

    public function test_order() {
        $response = $this->get('/pokemon/?_order=weight');

        $response->assertJsonCount(15);
        $response->assertJson([
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
        ]);
    }

    public function test_order_direction() {
        $response = $this->get('/pokemon/?_order=weight&_direction=ASC');

        $response->assertJsonCount(15);
        $response->assertJson([
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
        ]);
    }
}
