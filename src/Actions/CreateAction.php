<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait CreateAction {
    public function create(Request $request) {
        $this->authorize('create', $this->model);
        /** @var Builder $query */
        $query = $this->model::query();
        $instance = $query->newModelInstance();
        return $this->view('create', [
            $this->resolveModelName($request, false) => $instance
        ]);
    }
}
