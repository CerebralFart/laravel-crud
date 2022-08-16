<?php

namespace Cerebralfart\LaravelCRUD\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface FileNamingContract {
    /**
     * @param Model $model The model to which the file should be associated
     * @param string $attribute The attribute which the file is associated to
     * @param UploadedFile $file The file being uploaded
     * @return string The path to which the file should be saved
     */
    function determineFileName(
        Model        $model,
        string       $attribute,
        UploadedFile $file
    ): string;
}
