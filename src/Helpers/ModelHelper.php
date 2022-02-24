<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * @property-read class-string<Model> $model
 */
trait ModelHelper {
    protected function resolveModelName(Request $request): string {
        return Str::of($this->model)
            ->afterLast('\\')
            ->lower()
            ->singular()
            ->toString();
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
        $model->save();
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
        $model->{$key} = $value;
    }
}
