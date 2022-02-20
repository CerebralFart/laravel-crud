<?php

namespace Cerebralfart\LaravelCRUD;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @property-read class-string<Model> $model
 * @property-read string $views
 */
abstract class CRUDController extends Controller {
    protected function resolveModel($id) {
        return $this->getModel()::find($id);
    }

    /**
     * Returns the configured model for this controller, or throws an exception if it is not properly defined
     * @return class-string<Model>
     * @throws Exception
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
        $response = Gate::inspect('viewAny', $this->getModel());
        if ($response->denied())
            throw new AccessDeniedHttpException($response->message());

        return view($this->getView('list'), [
            'items' => $this->getModel()::all(),
        ]);
    }

    public function show(Request $request, string $id) {
        $instance = $this->resolveModel($id);
        if (!$instance)
            throw new NotFoundHttpException();

        $response = Gate::inspect('view', $instance);
        if ($response->denied())
            throw new NotFoundHttpException();

        return view($this->getView('show'), ['item' => $instance]);
    }

    public function create(Request $request) {
        $response = Gate::inspect('create', $this->getModel());
        if ($response->denied())
            throw new AccessDeniedHttpException($response->message());

        if ($request->method() === 'POST') {
            /** @var Builder $qb */
            $qb = $this->getModel()::query();
            $instance = $qb->newModelInstance();
            $this->updateModel($instance, $request);
            return redirect()->back();
        } else {
            return view($this->getView('create'));
        }
    }

    public function update(Request $request, string $id) {
        $instance = $this->resolveModel($id);
        if (!$instance)
            throw new NotFoundHttpException();

        $response = Gate::inspect('update', $instance);
        if ($response->denied())
            throw new NotFoundHttpException($response->message());

        if ($request->method() === 'POST') {
            $this->updateModel($instance, $request);
            return redirect()->back();
        } else {
            return view($this->getView('update'), ['item' => $instance]);
        }
    }

    public function delete(Request $request, string $id) {
        $instance = $this->resolveModel($id);
        if (!$instance)
            throw new NotFoundHttpException();

        $response = Gate::inspect('delete', $instance);
        if ($response->denied())
            throw new NotFoundHttpException($response->message());

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
