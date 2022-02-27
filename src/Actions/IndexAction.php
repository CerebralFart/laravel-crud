<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

// TODO [0.1.1] Allow filtering of items
// TODO [0.2.0] Allow searching of items via FT-search
trait IndexAction {
    public function index(Request $request) {
        $this->authorize('viewAny', $this->model);

        /** @var Builder $query */
        $query = $this->model::query();
        $query = $this->applySearch($request, $query);
        $query = $this->applyOrder($request, $query);

        return $this->view('index', [
            'items' => $query->get()
        ]);
    }
}
