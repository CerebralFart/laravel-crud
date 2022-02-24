<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait RouteHelper {
    protected function redirect(string $suffix, array $data = []): RedirectResponse {
        $current = Route::current();
        $prefix = Str::beforeLast($current->getName(), '.');
        $route = $prefix . '.' . $suffix;
        return redirect()->route($route, array_merge(
            $current->parameters,
            $data
        ));
    }
}
