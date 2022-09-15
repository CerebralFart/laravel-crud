<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait DestroyAction {
    public function destroy(Request $request) {
        $instance = $this->resolveModel($request);
        $this->authorize('delete', $instance);
        $instance->delete();
        return $this->destroyActionResponse($request, $instance);
    }

    public function destroyActionResponse(Request $request, Model $model) {
        return $this->redirect('index');
    }
}
