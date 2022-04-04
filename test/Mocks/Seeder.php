<?php

namespace Cerebralfart\LaravelCRUD\Test\Mocks;

class Seeder extends \Illuminate\Database\Seeder {
    public function run() {
        Pokemon::create(['id' => 1, 'name' => 'Bulbasaur', 'weight' => 6.9, 'height' => 0.7]);
        Pokemon::create(['id' => 2, 'name' => 'Ivysaur', 'weight' => 13.0, 'height' => 1.0]);
        Pokemon::create(['id' => 3, 'name' => 'Venusaur', 'weight' => 100.0, 'height' => 2.0]);

        Pokemon::create(['id' => 4, 'name' => 'Charmander', 'weight' => 8.5, 'height' => 0.6]);
        Pokemon::create(['id' => 5, 'name' => 'Charmeleon', 'weight' => 19.0, 'height' => 1.1]);
        Pokemon::create(['id' => 6, 'name' => 'Charizard', 'weight' => 90.5, 'height' => 1.7]);

        Pokemon::create(['id' => 7, 'name' => 'Squirtle', 'weight' => 9.0, 'height' => 0.5]);
        Pokemon::create(['id' => 8, 'name' => 'Wartortle', 'weight' => 22.5, 'height' => 1.0]);
        Pokemon::create(['id' => 9, 'name' => 'Blastoise', 'weight' => 85.5, 'height' => 1.6]);

        Pokemon::create(['id' => 10, 'name' => 'Caterpie', 'weight' => 2.9, 'height' => 0.3]);
        Pokemon::create(['id' => 11, 'name' => 'Metapod', 'weight' => 9.9, 'height' => 0.7]);
        Pokemon::create(['id' => 12, 'name' => 'Butterfree', 'weight' => 32.0, 'height' => 1.1]);

        Pokemon::create(['id' => 13, 'name' => 'Weedle', 'weight' => 3.2, 'height' => 0.3]);
        Pokemon::create(['id' => 14, 'name' => 'Kakuna', 'weight' => 10.0, 'height' => 0.6]);
        Pokemon::create(['id' => 15, 'name' => 'Beedrill', 'weight' => 29.5, 'height' => 1.0]);
    }
}
