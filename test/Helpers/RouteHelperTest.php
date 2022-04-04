<?php

namespace Cerebralfart\LaravelCRUD\Test\Helpers;

use Cerebralfart\LaravelCRUD\Helpers\RouteHelper;
use Cerebralfart\LaravelCRUD\Test\TestCase;
use Illuminate\Support\Facades\Route;

class RouteHelperTest extends TestCase {
    use RouteHelper;

    public function test_redirect() {
        $routes = Route::getRoutes();

        $mock = Route::partialMock();
        $mock->expects('current')->andReturn($routes->getByName('pokemon.index'));
        $mock->expects('getRoutes')->zeroOrMoreTimes()->andReturn($routes);

        $response = $this->redirect('edit', ['pokemon' => 3]);

        $this->assertStringEndsWith('/pokemon/3/edit', $response->getTargetUrl());
    }
}
