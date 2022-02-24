<?php

namespace Cerebralfart\LaravelCRUD\Actions;

use Illuminate\Http\Request;

// TODO [0.1.1] Allow customizing the response
// TODO [0.1.x] Redirect response should not include the ID of the removed user
trait DestroyAction {
    public function destroy(Request $request) {
        $instance = $this->resolveModel($request);
        $this->authorize('delete', $instance);
        $instance->delete();
        return $this->redirect('index');
    }
}
