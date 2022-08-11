<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Cerebralfart\LaravelCRUD\Helpers\ViewHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait UpdateAction {
    use EditAction;
    use ViewHelper;

    public function update(Request $request) {
        $instance = $this->resolveModel($request);
        $this->authorize('update', $instance);
        $this->updateModel($instance, $request);
        $this->validateModel($instance);

        if ($this->viewHasShared('errors')) {
            return $this->edit($request);
        } else {
            $instance->save();
            return $this->updateActionResponse($request, $instance);
        }
    }

    protected function updateActionResponse(Request $request, Model $model) {
        return $this->redirect('show', [
            $this->resolveModelName($request, false) => $model,
        ]);
    }
}
