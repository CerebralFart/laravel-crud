<?php

namespace Cerebralfart\LaravelCRUD\Test;

use Cerebralfart\LaravelCRUD\Test\Mocks\Controller;
use Cerebralfart\LaravelCRUD\Test\Mocks\Pokemon;
use Cerebralfart\LaravelCRUD\Test\Mocks\Policy;
use Cerebralfart\LaravelCRUD\Test\Mocks\Seeder;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase {
    use RefreshDatabase;

    protected function defineEnvironment($app) {
        $app['config']->set('app.debug', true);
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('view.paths', [
            realpath("./test/")
        ]);

        Gate::policy(Pokemon::class, Policy::class);
    }

    protected function defineDatabaseMigrations() {
        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--path' => "../../../../test/Mocks/Migration.php"
        ])->run();
        $this->seed(Seeder::class);
    }

    protected function defineRoutes($router) {
        $router->resource('pokemon', Controller::class);
    }


    public function pass(?string $message): void {
        $this->assertTrue(true, $message);
    }
}
