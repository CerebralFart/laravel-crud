<?php

namespace Cerebralfart\LaravelCRUD;

use Cerebralfart\LaravelCRUD\Helpers\AuthHelper;
use Cerebralfart\LaravelCRUD\Helpers\PropHelper;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

/**
 * @property-read class-string<Model> $model
 * @property-read string $views
 */
abstract class CRUDController extends Controller {
    use AuthHelper, PropHelper;

    protected function resolveModel($id) {
        return $this->getModel()::find($id);
    }

    /**
     * Returns the configured model for this controller, or throws an exception if it is not properly defined
     * @return class-string<Model>
     * @throws Exception
     * @deprecated
     */
    protected function getModel(): string {
        if ($this->model === null)
            throw new Exception(sprintf('Model is not defined for %s', get_class($this)));

        return $this->model;
    }

    /**
     * @deprecated
     */
    protected function getView($name = null) {
        if ($this->views === null)
            throw new Exception(sprintf('View-space is not defined for %s', get_class($this)));

        return $name === null
            ? $this->views
            : sprintf('%s.%s', $this->views, $name);
    }

    public function list(Request $request) {
        $this->authorize('viewAny', $this->getModel());

        return view($this->getView('list'), [
            'items' => $this->getModel()::all(),
        ]);
    }

    public function show(Request $request, string $id) {
        $instance = $this->resolveModel($id);
        $this->authorize('view', $instance);

        return view($this->getView('show'), ['item' => $instance]);
    }

    public function create(Request $request) {
        $this->authorize('create', $this->getModel());

        /** @var Builder $qb */
        $qb = $this->getModel()::query();
        $instance = $qb->newModelInstance();

        if ($request->method() === 'POST') {
            $this->updateModel($instance, $request);
            return redirect()->back();
        } else {
            return view($this->getView('create'), ['item' => $instance]);
        }
    }

    public function update(Request $request, string $id) {
        $instance = $this->resolveModel($id);
        $this->authorize('update', $instance);

        if ($request->method() === 'POST') {
            $this->updateModel($instance, $request);
            return redirect()->back();
        } else {
            return view($this->getView('update'), ['item' => $instance]);
        }
    }

    public function delete(Request $request, string $id) {
        $instance = $this->resolveModel($id);
        $this->authorize('delete', $instance);

        if ($request->isMethod('POST')) {
            $instance->delete();
            return redirect()->back();
        } else {
            return view($this->getView('delete'), ['item' => $instance]);
        }
    }

    /*
     * ===================
     *    MODEL HELPERS
     * ===================
     */
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
