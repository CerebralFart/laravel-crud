<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

/**
 * @property-read class-string<Model> $model
 * @property-read array<string, string> $validationRules
 */
trait ModelHelper {
    protected array $defaultValidationRules = [];

    protected function resolveModelName(Request $request, bool $plural): string {
        $str = Str::lower(Str::of($this->model)
            ->afterLast('\\')
            ->split('/(?=[A-Z])/')
            ->last());

        return $plural
            ? Str::plural($str)
            : Str::singular($str);
    }

    protected function resolveModelId(Request $request): string {
        return last(Route::current()->parameters);
    }

    protected function resolveModel(Request $request): ?Model {
        return $this->model::find($this->resolveModelId($request));
    }

    protected function updateModel(Model $model, Request $request): void {
        $data = $request->input();
        foreach ($data as $key => $value) {
            $this->updateField($model, $key, $value);
        }
    }

    protected function updateField(Model $model, string $key, mixed $value): void {
        if (Str::startsWith($key, '_')) return;

        if ($model->isRelation($key)) $this->updateRelation($model, $key, $value);
        elseif ($model->isFillable($key)) $this->updateAttribute($model, $key, $value);
        else throw new Exception("Parameter ${key} could not be persisted");
    }

    protected function updateRelation(Model $model, string $key, mixed $value): void {
        $relation = $model->{$key}();
        if (method_exists($relation, 'sync')) {
            $relation->sync($value === null ? [] : $value);
        } else {
            $relation->associate($value);
        }
    }

    protected function updateAttribute(Model $model, string $key, mixed $value): void {
        $model->setAttribute($key, $value);
    }

    protected function validateModel(Model $model): ?MessageBag {
        $rules = $this->selectValidationRules($model, $this->validationRules);
        $validator = Validator::make($model->getAttributes(), $rules);
        return $validator->fails()
            ? $validator->errors()
            : null;
    }

    protected function selectValidationRules(Model $model, array $rules): array {
        return Arr::only($rules, array_keys($model->getDirty()));
    }
}
