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

    protected array $exposed = [];
    protected array $shared = [];

    protected function exposeToView(string $name, mixed $data): void {
        $this->exposed = array_merge_recursive(
            $this->exposed,
            [$name => $data]
        );
    }

    public function shareToView(string $name, mixed $data): void {
        $this->shared = array_merge_recursive(
            $this->shared,
            [$name => $data]
        );
    }

    public function viewHasExposed(string $name): bool {
        return array_key_exists($name, $this->exposed);
    }

    public function viewHasShared(string $name): bool {
        return array_key_exists($name, $this->shared);
    }

    protected function view(string $name, array $data = []): View {
        if ($this->hasErrors()) {
            ViewFacade::share('errors', $this->getErrors());
        }
        ViewFacade::share($this->shared);
        $chain = $this->viewMap[$name] ?? static::$defaultViewMap[$name];
        foreach ($chain as $option) {
            $view = $this->views . '.' . $option;
            if (ViewFacade::exists($view)) return ViewFacade::make(
                $view,
                array_merge($this->exposed, $data)
            );
        }
        throw new Exception("Could not find a view for $name");
    }
}
