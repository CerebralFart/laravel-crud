<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Http\Request;

// TODO [0.1.1] Allow customizing the response
// TODO [0.1.1] Add validation of dirty fields
// TODO [0.1.2] Allow checking policies through `BelongsTo` relations
trait UpdateAction {
    public function update(Request $request) {
        $instance = $this->resolveModel($request);
        $this->authorize('update', $instance);
        $this->updateModel($instance, $request);
        $instance->save();
        return $this->redirect('edit');
    }
}
