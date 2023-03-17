<?php

namespace Cerebralfart\LaravelCRUD\Test\Helpers;

use Cerebralfart\LaravelCRUD\Helpers\OrderHelper;
use Cerebralfart\LaravelCRUD\Test\Mocks\Pokemon;
use Cerebralfart\LaravelCRUD\Test\TestCase;
use Illuminate\Http\Request;

class OrderHelperTest extends TestCase {
    use OrderHelper;


    protected function setUp(): void {
        parent::setUp();
        $this->orderColumn = null;
        $this->orderDirection = 'DESC';
    }

    public function test_does_nothing_without_order_parameter() {
        $request = Request::create('/');
        $query = Pokemon::query();
        $sql = $query->toSql();

        $this->applyOrder($request, $query);
        $this->assertEquals($sql, $query->toSql());
    }

    public function test_respects_default_order_column() {
        $request = Request::create('/');
        $query = Pokemon::query();
        $this->orderColumn = 'test_column';

        $this->applyOrder($request, $query);
        $this->assertStringEndsWith('order by "test_column" desc', $query->toSql());

        unset($this->orderColumn);
    }

    public function test_orders_on_order_parameter() {
        $request = Request::create('/?_order=test_column');
        $query = Pokemon::query();

        $this->applyOrder($request, $query);
        $this->assertStringEndsWith('order by "test_column" desc', $query->toSql());
    }

    public function test_respects_order_direction() {
        $request = Request::create('/?_order=test_column&_direction=asc');
        $query = Pokemon::query();

        $this->applyOrder($request, $query);
        $this->assertStringEndsWith('order by "test_column" asc', $query->toSql());
    }
}
