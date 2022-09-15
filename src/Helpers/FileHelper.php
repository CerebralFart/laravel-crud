<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Cerebralfart\LaravelCRUD\Contracts\FileNamingContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @mixin FileNamingContract
 */
trait FileHelper {
    use ModelHelper, ValidationHelper;

    /** @var array<int, string> */
    public array $files = [];
    /** @var array<string, string> */
    public array $fileValidation = [];
    /** @var string|null The disk to save the file on, setting to null will use the default disk */
    public ?string $fileDisk = null;
    /** @var string|null The folder to save the file in, setting to null will save the file in the root folder */
    public ?string $fileFolder = null;
    public array $fileOptions = [];

    public function updateFiles(Model $model, Request $request): void {
        $files = array_intersect(array_keys($_FILES), $this->files);
        foreach ($files as $name) {
            /** @var UploadedFile|null $file */
            $file = $request->file($name);
            $this->updateFile($model, $name, $file);
        }
    }

    public function updateFile(Model $model, string $attribute, ?UploadedFile $file): void {
        if (!$this->validateFile($attribute, $file)) return;

        if ($file === null) {
            $this->updateField($model, $attribute, null);
        } else {
            $fullPath = $this->normalizeFilePath(
                $this->fileFolder,
                $this->determineFileName($model, $attribute, $file)
            );

            $wasSaved = Storage::disk($this->fileDisk)->put(
                $fullPath,
                $file->getContent(),
                $this->fileOptions
            );
            if ($wasSaved === false) {
                $this->addError($attribute, ['File could not be saved']);
            } else {
                $this->updateField($model, $attribute, $fullPath);
            }
        }
    }

    public function validateFile(string $attribute, ?UploadedFile $file): bool {
        if (array_key_exists($attribute, $this->fileValidation)) {
            return $this->validate(
                [$attribute => $file],
                [$attribute => $this->fileValidation[$attribute]]
            );
        }
        return true;
    }

    public function determineFileName(Model $model, string $attribute, UploadedFile $file): string {
        return $file->hashName();
    }

    public function normalizeFilePath(?string ...$sections): string {
        return Str::of(collect($sections)
            ->filter(fn(?string $val) => $val !== null)
            ->join('/'))
            ->replaceMatches('/\/{2,}/', '/')
            ->ltrim('/');
    }
}
