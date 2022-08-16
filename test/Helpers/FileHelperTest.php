<?php

namespace Cerebralfart\LaravelCRUD\Test\Helpers;

use Cerebralfart\LaravelCRUD\Helpers\FileHelper;
use Cerebralfart\LaravelCRUD\Test\Mocks\Pokemon;
use Cerebralfart\LaravelCRUD\Test\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Session;

class FileHelperTest extends TestCase {
    use FileHelper;

    protected function setUp(): void {
        parent::setUp();
        $this->fileValidation = [];
    }

    public function test_update_file() {
        $this->fileValidation = ['image' => 'image'];
        $model = new Pokemon();
        $model->setAttribute('image', 'old-image.jpg');
        $this->updateFile($model, 'image', File::image('image.jpg'));
        $this->assertNotEquals('old-image.jpg', $model->getAttribute('image'));
        $this->assertEmpty(Session::get('errors'));
    }

    public function test_update_file_null() {
        $model = new Pokemon();
        $model->setAttribute('image', 'old-image.jpg');
        $this->updateFile($model, 'image', null);
        $this->assertNull($model->getAttribute('image'));
    }

    public function test_update_file_invalid() {
        $this->fileValidation = ['image' => 'image'];
        $model = new Pokemon();
        $model->setAttribute('image', 'old-image.jpg');
        $this->updateFile($model, 'image', File::create('document.txt'));
        $this->assertEquals('old-image.jpg', $model->getAttribute('image'));
        $this->assertEquals(['image' => ['The image must be an image.']], Session::get('errors'));
    }

    public function test_validate_file() {
        $this->fileValidation = ['file' => 'required|image'];
        $this->assertFalse($this->validateFile('file', File::create('document.txt')));
        $this->assertTrue($this->validateFile('file', File::image('image.jpg')));
    }

    public function test_normalize_file_path() {
        $this->assertEquals($this->normalizeFilePath('file.jpg'), 'file.jpg');
        $this->assertEquals($this->normalizeFilePath('/file.jpg'), 'file.jpg');
        $this->assertEquals($this->normalizeFilePath(null, 'file.jpg'), 'file.jpg');
        $this->assertEquals($this->normalizeFilePath('folder', 'file.jpg'), 'folder/file.jpg');
        $this->assertEquals($this->normalizeFilePath('folder', null, 'file.jpg'), 'folder/file.jpg');
    }
}
