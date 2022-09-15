<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait ModelHelper {
    /** @var ?class-string<Model> The model to be used in this controller */
    public ?string $model;
    /** @var null|string|array<int, string> The name to be used when exposing this model to views */
    public $modelName = null;

    public function guessModelName(Request $request):string{
        return Str::lower(Str::of($this->model)
            ->afterLast('\\')
            ->split('/(?=[A-Z])/')
            ->last());
    }

    public function resolveModelName(Request $request, bool $plural): string {
        // If no explicit model name has been defined, we guess it.
        // The exact logic for this can be overridden via traits
        if ($this->modelName === null) {
            $this->modelName = $this->guessModelName($request);
        }

        // If the model name is a string, we conjugate it and store it for later use
        // This is also done with the results from the guessModelName function
        if (is_string($this->modelName)) {
            $this->modelName = [
                Str::singular($this->modelName),
                Str::plural($this->modelName),
            ];
        }

        // By this point, the modelName attribute should be an array
        return $this->modelName[$plural ? 1 : 0];
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
        $this->updateFiles($model, $request);
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
}
