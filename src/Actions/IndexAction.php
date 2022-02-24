<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Http\Request;

// TODO [0.1.1] Allow filtering of items
// TODO [0.1.1] Allow ordering of items
// TODO [0.1.1] Allow searching of items
// TODO [0.1.2] Allow searching of items via FT-search
trait IndexAction {
    public function index(Request $request) {
        $this->authorize('viewAny', $this->model);

        return $this->view('index', [
            'items' => $this->model::all(),
        ]);
    }
}
