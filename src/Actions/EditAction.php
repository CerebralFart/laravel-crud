<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Http\Request;

trait EditAction {
    public function edit(Request $request) {
        $instance = $this->resolveModel($request);
        $this->authorize('update', $instance);
        return $this->view('edit', [
            $this->resolveModelName($request, false) => $instance
        ]);
    }
}
