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
        $r = $this->artisan('migrate', [
            '--database' => 'testbench',
            '--path' => "../../../../test/Mocks/Migration.php"
        ])->run();
        $this->seed(Seeder::class);
    }

    protected function defineRoutes($router) {
        $router->resource('/', Controller::class);
    }


    public function assertThrows(callable $callback, string $type = null, callable $assertions = null) {
        try {
            $callback();
            $this->fail("Expected an exception to be thrown");
        } catch (Exception $exception) {
            $this->assertTrue(true);
            if ($type !== null) $this->assertEquals($type, get_class($exception));
            if ($assertions !== null) $assertions($exception);
        }
    }
}
