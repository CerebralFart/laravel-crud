<?php

namespace Cerebralfart\LaravelCRUD\Test\Helpers;

use Cerebralfart\LaravelCRUD\Helpers\ModelHelper;
use Cerebralfart\LaravelCRUD\Test\TestCase;
use Illuminate\Http\Request;

class ModelHelperTest extends TestCase {
    use ModelHelper;

    public function test_resolve_model_name() {
        $this->modelName = null;
        $this->model = 'model';
        $this->assertEquals('model', $this->resolveModelName(new Request(), false));
        $this->assertEquals('models', $this->resolveModelName(new Request(), true));
        unset($this->model);
    }

    public function test_resolve_complex_names() {
        $this->modelName = null;
        $this->model = 'App\Models\UserActions';
        $this->assertEquals('action', $this->resolveModelName(new Request(), false));
        $this->assertEquals('actions', $this->resolveModelName(new Request(), true));
        unset($this->model);
    }

    public function test_respects_model_name() {
        $this->modelName = 'lineItem';
        $this->assertEquals('lineItem', $this->resolveModelName(new Request(), false));
        $this->assertEquals('lineItems', $this->resolveModelName(new Request(), true));
        unset($this->modelName);
    }

    public function test_respects_model_names() {
        $this->modelName = ['something', 'somethingDifferent'];
        $this->assertEquals('something', $this->resolveModelName(new Request(), false));
        $this->assertEquals('somethingDifferent', $this->resolveModelName(new Request(), true));
        unset($this->modelName);
    }
}
