<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

// TODO [0.1.1] Allow customizing the response
// TODO [0.1.1] Add validation of all fields
// TODO [0.1.2] Allow checking policies through `BelongsTo` relations
trait StoreAction {
    public function store(Request $request) {
        $this->authorize('create', $this->model);
        /** @var Builder $query */
        $query = $this->model::query();
        $instance = $query->newModelInstance();
        $this->updateModel($instance, $request);
        $instance->save();
        return $this->redirect('show', [
            $this->resolveModelName($request) => $instance
        ]);
    }
}
