<?php

namespace Cerebralfart\LaravelCRUD\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ValidationContract {
    /**
     * @param Model $model The model to be validated
     * @param array $rules The developer-defined set of validation rules
     * @return array The selected subset of validation rules
     */
    function selectValidationRules(
        Model $model,
        array $rules,
    ): array;
}
