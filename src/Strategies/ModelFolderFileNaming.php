<?php

namespace Cerebralfart\LaravelCRUD\Strategies;

use Cerebralfart\LaravelCRUD\Contracts\FileNamingContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/**
 * @mixin FileNamingContract
 */
trait ModelFolderFileNaming {
    public function determineFileName(Model $model, string $attribute, UploadedFile $file): string {
        return sprintf("%s/%s.%s",
            $model->getKey(),
            $attribute,
            $file->extension()
        );
    }
}
