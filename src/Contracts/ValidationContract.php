<?php

namespace Cerebralfart\LaravelCRUD\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ValidationContract {
    /**
     * @param Model $model The model to be validated
     * @param array<string, string> $rules The developer-defined set of validation rules
     * @return array<string, string> The selected subset of validation rules
     */
    function selectValidationRules(
        Model $model,
        array $rules,
    ): array;
}
