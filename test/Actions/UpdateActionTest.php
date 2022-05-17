<?php

namespace Cerebralfart\LaravelCRUD\Test\Actions;

use Cerebralfart\LaravelCRUD\Test\Mocks\Pokemon;
use Cerebralfart\LaravelCRUD\Test\TestCase;

class UpdateActionTest extends TestCase {
    public function test_can_update() {
        $response = $this->put('/pokemon/7', [
            'weight' => 10.0,
        ]);

        $response->assertRedirect('/pokemon/7');
        $this->assertEquals([
            'id' => 7,
            'name' => 'Squirtle',
            'weight' => 10,
            'height' => 0.5
        ], Pokemon::find(7)->toArray());
    }

    public function test_object_validation() {
        $response = $this->put('/pokemon/7', [
            'name' => 'xx',
        ]);

        $response->assertJsonPath('errors.name', ['The name must be at least 3 characters.']);
    }
}
