<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait StoreAction {
    public function store(Request $request) {
        $this->authorize('create', $this->model);
        /** @var Builder $query */
        $query = $this->model::query();
        $instance = $query->newModelInstance();
        $this->updateModel($instance, $request);
        $instance->save();
        return $this->storeActionResponse($request, $instance);
    }

    protected function storeActionResponse(Request $request, Model $model) {
        return $this->redirect('show', [
            $this->resolveModelName($request) => $model,
        ]);
    }
}
