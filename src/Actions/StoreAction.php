<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Cerebralfart\LaravelCRUD\Helpers\ViewHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait StoreAction {
    use CreateAction;
    use ViewHelper;

    public function store(Request $request) {
        $this->authorize('create', $this->model);
        /** @var Builder $query */
        $query = $this->model::query();
        $instance = $query->newModelInstance();
        $this->updateModel($instance, $request);
        $this->validateModel($instance);

        if ($this->viewHasShared('errors')) {
            return $this->create($request);
        } else {
            $instance->save();
            return $this->storeActionResponse($request, $instance);
        }
    }

    protected function storeActionResponse(Request $request, Model $model) {
        return $this->redirect('show', [
            $this->resolveModelName($request, false) => $model,
        ]);
    }
}
