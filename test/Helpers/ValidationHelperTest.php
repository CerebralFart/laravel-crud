<?php

namespace Cerebralfart\LaravelCRUD\Test\Helpers;

use Cerebralfart\LaravelCRUD\Helpers\ValidationHelper;
use Cerebralfart\LaravelCRUD\Test\TestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

class ValidationHelperTest extends TestCase {
    use ValidationHelper;

    protected function setUp(): void {
        parent::setUp();
        Session::flush();
    }

    public function test_add_error() {
        $this->addError('field', ['an error']);
        $errors = Session::get('errors');
        $this->assertEquals($errors, ['field' => ['an error']]);
    }

    public function test_add_error_multiple() {
        $this->addError('field1', ['one error']);
        $this->addError('field1', ['another error']);
        $this->addError('field2', ['totally separate field']);
        $errors = Session::get('errors');
        $this->assertEquals($errors, ['field1' => ['one error', 'another error'], 'field2' => ['totally separate field']]);
    }

    public function test_add_errors() {
        $this->addErrors(new MessageBag([
            'field1' => ['one error'],
            'field2' => ['totally separate field'],
        ]));
        $errors = Session::get('errors');
        $this->assertEquals($errors, ['field1' => ['one error'], 'field2' => ['totally separate field']]);
    }

    public function test_add_errors_argument_isolation() {
        $bag = new MessageBag();
        $this->addErrors($bag);
        $this->addError('field', ['test']);
        $this->assertEmpty($bag);
    }

    public function test_has_error() {
        $this->assertFalse($this->hasErrors());
        $this->addError('field', ['an error']);
        $this->assertTrue($this->hasErrors());
        Session::flush();
        $this->assertFalse($this->hasErrors());
        Session::put('errors', []);
        $this->assertFalse($this->hasErrors());
    }

    public function test_get_errors() {
        $this->assertEmpty($this->getErrors());
        $this->addError('field', ['one error']);
        $this->assertInstanceOf(MessageBag::class, $this->getErrors());
        $this->assertEquals($this->getErrors()->toArray(), ['field' => ['one error']]);
        $this->addError('field', ['another error']);
        $this->assertEquals($this->getErrors()->toArray(), ['field' => ['one error', 'another error']]);
    }

    public function test_validate() {
        $this->assertFalse($this->validate(
            ['field' => 'not-an-url'],
            ['field' => 'url']
        ));
        $this->assertTrue($this->hasErrors());
        $this->assertEquals($this->getErrors()->toArray(), ['field' => ['The field must be a valid URL.']]);
    }

    public function test_validate_successful() {
        $this->assertTrue($this->validate(
            ['field' => 'http://github.com'],
            ['field' => 'url']
        ));
        $this->assertFalse($this->hasErrors());
        $this->assertEmpty($this->getErrors());
    }

    public function test_validate_multiple() {
        $this->validate(
            ['field1' => 'not-an-url'],
            ['field1' => 'url']
        );
        $this->validate(
            ['field2' => 'non-numeric'],
            ['field2' => 'integer']
        );
        $this->assertTrue($this->hasErrors());
        $this->assertEquals($this->getErrors()->toArray(), [
            'field1' => ['The field1 must be a valid URL.'],
            'field2' => ['The field2 must be an integer.'],
        ]);
    }
}
