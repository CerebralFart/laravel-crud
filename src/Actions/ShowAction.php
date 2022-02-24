<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Http\Request;

trait ShowAction {
    public function show(Request $request) {
        $instance = $this->resolveModel($request);
        $this->authorize('view', $instance);
        return $this->view('show', [
            $this->resolveModelName($request) => $instance
        ]);
    }
}
