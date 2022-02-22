<?php

namespace Cerebralfart\LaravelCRUD\Test;

use Exception;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase {
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
