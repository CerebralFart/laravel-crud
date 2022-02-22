<?php

namespace Cerebralfart\LaravelCRUD\Test\Helpers;

use Cerebralfart\LaravelCRUD\Helpers\PropHelper;
use Cerebralfart\LaravelCRUD\Test\TestCase;
use Exception;

class PropHelperTest extends TestCase {
    private PropHelperMock $mock;

    public function setUp(): void {
        parent::setUp();
        $this->mock = new PropHelperMock();
    }

    public function test_throws_on_required_props() {
        $this->assertThrows(
            fn() => $this->mock->model,
            \Exception::class,
            function (Exception $exception) {
                $this->assertStringContainsString('PropHelperMock', $exception->getMessage());
                $this->assertStringContainsString('$model', $exception->getMessage());
            }
        );
        $this->assertThrows(
            fn() => $this->mock->views,
            \Exception::class,
            function (Exception $exception) {
                $this->assertStringContainsString('PropHelperMock', $exception->getMessage());
                $this->assertStringContainsString('$views', $exception->getMessage());
            }
        );
    }

    public function test_resolves_instance_property() {
        $this->assertEquals('defaultInstanceProp', $this->mock->instanceProp);
    }

    public function test_resolves_instance_function() {
        $this->assertEquals('defaultInstanceFn', $this->mock->instanceFn);
    }

    public function test_resolves_static_property() {
        $this->assertEquals('defaultStaticProp', $this->mock->staticProp);
    }

    public function test_resolves_static_function() {
        $this->assertEquals('defaultStaticFn', $this->mock->staticFn);
    }

    public function test_undefined_default() {
        $this->assertNull($this->mock->missing);
    }
}

class PropHelperMock {
    use PropHelper;

    private static string $defaultStaticProp = 'defaultStaticProp';

    private static function defaultStaticFn() {
        return 'defaultStaticFn';
    }

    private string $defaultInstanceProp = 'defaultInstanceProp';

    private function defaultInstanceFn() {
        return 'defaultInstanceFn';
    }
}
