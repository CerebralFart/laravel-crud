<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

/**
 * @property-read string $views
 * @property-read array<string, string[]> $viewMap
 */
trait ViewHelper {
    protected static $defaultViewMap = [
        'index' => ['index', 'list'],
        'create' => ['create', 'upsert'],
        'show' => ['show'],
        'edit' => ['edit', 'update', 'upsert'],
    ];

    protected array $viewData = [];

    protected function exposeToView(string $name, mixed $data): void {
        $this->viewData[$name] = $data;
    }

    protected function view(string $name, array $data = []): View {
        $chain = $this->viewMap[$name] ?? static::$defaultViewMap[$name];
        foreach ($chain as $option) {
            $view = $this->views . '.' . $option;
            if (ViewFacade::exists($view)) return view(
                $view,
                array_merge($this->viewData, $data)
            );
        }
        throw new Exception("Could not find a view for $name");
    }
}
