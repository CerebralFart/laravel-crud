<?php

namespace Cerebralfart\LaravelCRUD\Test\Actions;

use Cerebralfart\LaravelCRUD\Test\TestCase;

class StoreActionTest extends TestCase {
    public function test_can_store() {
        $response = $this->post('/pokemon/', [
            'name' => 'Pikachu',
            'weight' => 6.0,
            'height' => 0.4
        ]);

        $response->assertRedirect('/pokemon/16');
    }

    public function test_object_validation() {
        $response = $this->post('/pokemon/', [
            'name' => 'xx',
            'weight' => 6.9,
            'height' => 0.7
        ]);

        $response->assertJsonPath('errors.name', ['The name must be at least 3 characters.']);
    }
}
