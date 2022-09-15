<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait RouteHelper {
    public function redirect(string $suffix, array $data = []): RedirectResponse {
        $current = Route::current();
        $prefix = Str::beforeLast($current->getName(), '.');
        $route = $prefix . '.' . $suffix;

        // Only copy over the parameters which are used by the new route
        // Prevents nasty URLs like /users?user=42 when going from .edit to .index
        $implicitParameters = Arr::only(
            $current->parameters ?? [],
            Route::getRoutes()->getByName($route)->parameterNames(),
        );

        return redirect()->route($route, array_merge(
            $implicitParameters,
            $data
        ));
    }
}
