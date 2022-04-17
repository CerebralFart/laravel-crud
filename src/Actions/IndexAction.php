<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait IndexAction {
    public function index(Request $request) {
        $this->authorize('viewAny', $this->model);

        /** @var Builder $query */
        $query = $this->model::query();
        $query = $this->applyFilter($request, $query);
        $query = $this->applySearch($request, $query);
        $query = $this->applyOrder($request, $query);

        return $this->view('index', [
            $this->resolveModelName($request, true) => $query->get()
        ]);
    }
}
